<?php

namespace AKlump\Taskcamp\API;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Yaml\Yaml;

/**
 * An encoder/decoder for application/prs.taskcamp.entity MIME types.
 */
class EntityEncoder implements EncoderInterface, DecoderInterface {

  const TYPE = 'taskcamp_entity';

  /**
   * Encodes data into the given format.
   *
   * @param mixed $data Data to encode
   * @param string $format Format name
   *   Only one format is supported, 'taskcamp_entity'.
   * @param array $context Options that normalizers/encoders have access to
   *   - mode string One of relaxed|strict.
   *
   * @return string
   *
   * @throws UnexpectedValueException
   */
  public function encode($data, $format, array $context = []): string {

    $context += [
      'mode' => 'strict',
    ];
    $context = [
        'self_closing' => $context['mode'] === 'strict',
        'quote_properties' => $context['mode'] === 'strict',
        'frontmatter_prefix' => $context['mode'] === 'strict',
      ] + $context;

    $lines = [];

    // Header.
    $properties = '';
    if (!empty($data['properties'])) {
      $properties = [];
      foreach ($data['properties'] as $name => $value) {
        $properties[] = "$name=" . (strstr($value, ' ') === FALSE && $context['quote_properties'] === FALSE ? $value : "\"$value\"");
      }
      $properties = ' ' . implode(' ', $properties);
    }

    $self_closing = $context['self_closing'] ? '/' : '';

    if (empty($data['type'])) {
      throw new SyntaxErrorException('Missing entity type');
    }
    $lines[] = "<{$data['type']}{$properties}{$self_closing}>";

    if (!empty($data['data'])) {
      if ($context['frontmatter_prefix']) {
        $lines[] = '---';
      }
      $lines[] = trim(Yaml::dump($data['data']), PHP_EOL);
      $lines[] = '---';
    }

    $lines[] = '# ' . $data['title'];

    $body = trim($data['body'], PHP_EOL);
    if ($body) {
      $lines[] = PHP_EOL . $body;
    }

    return trim(implode(PHP_EOL, $lines), PHP_EOL) . PHP_EOL;
  }

  /**
   * Checks whether the serializer can encode to given format.
   *
   * @param string $format Format name
   *
   * @return bool
   */
  public function supportsEncoding($format) {
    return $format === self::TYPE;
  }


  /**
   * Checks whether the deserializer can decode from given format.
   *
   * @param string $format Format name
   *
   * @return bool
   */
  public function supportsDecoding($format) {
    return $this->supportsEncoding($format);
  }

  /**
   * Decodes a string into PHP data.
   *
   * @param string $data Data to decode
   * @param string $format Format name
   *   Only one format is supported, 'taskcamp_entity'.
   * @param array $context Options that decoders have access to
   *
   * The format parameter specifies which format the data is in; valid values
   * depend on the specific implementation. Authors implementing this interface
   * are encouraged to document which formats they support in a non-inherited
   * phpdoc comment.
   *
   * @return mixed
   *
   * @throws UnexpectedValueException
   */
  public function decode($data, $format, array $context = []) {
    $decoded = [
      'type' => '',
      'properties' => [],
      'data' => [],
    ];

    // Preprocess whitespace.
    $data = str_replace("\r\n", PHP_EOL, $data);
    $data = trim($data);

    // Divide into major sections.
    preg_match_all('/^\-\-\-/m', $data, $matches);
    $frontmatter = NULL;
    switch (count($matches[0])) {
      case 0:
        $data = explode("\n", $data, 2);
        $header = $data[0];
        $decoded['body'] = $data[1] ?? '';
        break;

      case 1:
        list($header, $decoded['body']) = explode("---\n", $data, 2);
        $header = trim($header);
        break;

      case 2:
      default:
        list($header, $frontmatter, $decoded['body']) = explode("---\n", $data, 3);
        $header = trim($header);
        break;
    }

    // Parse the header and properties.
    preg_match('/<\s*([a-z]+)(?:\s+(.+))?\/?>/', $header, $matches);
    if (!$matches) {
      throw new SyntaxErrorException('Missing or malformed header');
    }
    $decoded['type'] = $matches[1];
    if (!empty($matches[2])) {
      $has_unquoted_spaces = preg_match('/=[^"\s]+ [^">]/', $matches[2]);
      if ($has_unquoted_spaces) {
        throw new SyntaxErrorException('Property values containing spaces must be double quoted.');
      }
      parse_str($matches[2], $parsed);

      // Typecast and trip quotes from properties.
      $decoded['properties'] = array_map(function ($value) {
        $value = trim($value, '"');
        if (is_numeric($value)) {
          $value *= 1;
        }

        return $value;
      }, $parsed);
    }


    if ($frontmatter) {
      $decoded['data'] = Yaml::parse($frontmatter);
    }

    // Make sure the body has a title and move it.
    preg_match('/^#\s*([^#][^\n]+)(?:\n\s*(.+))?/s', $decoded['body'], $matches);
    if (empty($matches[1])) {
      throw new SyntaxErrorException("The body must being with a heading designated with a single hash (#) followed by a string of text.");
    }
    $decoded['title'] = $matches[1];
    $decoded['body'] = $matches[2] ?? '';

    return $decoded;
  }

}

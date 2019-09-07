<?php

namespace AKlump\Taskcamp\API;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Yaml\Yaml;

/**
 * An encoder/decoder for application/prs.taskcamp.obj MIME types.
 */
class EntityEncoder implements EncoderInterface, DecoderInterface {

  /**
   * Encodes data into the given format.
   *
   * @param mixed $data Data to encode
   * @param string $format Format name
   * @param array $context Options that normalizers/encoders have access to
   *
   * @return string|int|float|bool
   *
   * @throws UnexpectedValueException
   */
  public function encode($data, $format, array $context = []) {
    $lines = [];

    // Header.
    $properties = '';
    if (!empty($data['properties'])) {
      $properties = [];
      foreach ($data['properties'] as $name => $value) {
        $properties[] = "$name=" . (strstr($value, ' ') === FALSE ? $value : "\"$value\"");
      }
      $properties = ' ' . implode(' ', $properties);
    }
    $lines[] = "<{$data['type']}{$properties}>";

    if (!empty($data['data'])) {
      $lines[] = trim(Yaml::dump($data['data']), PHP_EOL);
    }
    $lines[] = '---';

    $lines[] = '# ' . $data['title'];
    $lines[] = NULL;
    $lines[] = trim($data['body'], PHP_EOL);

    return implode(PHP_EOL, $lines);
  }

  /**
   * Checks whether the serializer can encode to given format.
   *
   * @param string $format Format name
   *
   * @return bool
   */
  public function supportsEncoding($format) {
    $short = str_replace('application/prs.', '', MimeTypes::OBJECT);

    return $format === MimeTypes::OBJECT || $format === $short;
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
    $lines = explode(PHP_EOL, $data);
    $has_header = preg_match('/^<[^<]+>*$/', trim($lines[0]));
    $has_frontmatter = count(array_filter($lines, function ($item) {
        return substr($item, 0, 3) === '---';
      })) > 0;

    if ($has_header) {
      $header = array_shift($lines);
      preg_match('/<\s*([a-z]+)\s+(.+)>/', $header, $matches);
      $decoded['type'] = $matches[1];
      preg_match_all('/(.+)=(.+)/', $matches[2], $matches2, PREG_SET_ORDER);
      foreach ($matches2 as $item) {
        $value = $item[2];
        $value = preg_replace('/^["\']/', '', $value);
        $value = preg_replace('/["\']$/', '', $value);
        $decoded['properties'][$item[1]] = $value;
      }
    }

    // Remove optional top line beginning with "---".
    if ($has_frontmatter) {
      if (substr($lines[0], 0, 3) === '---') {
        array_shift($lines);
      }
      $has_frontmatter = !preg_match('/^#[^#]/', $lines[0]);
      $decoded['body'] = implode(PHP_EOL, $lines);
      if ($has_frontmatter) {
        $lines = explode('---', $decoded['body']) + ['', ''];
        if (count($lines) > 2) {
          throw new SyntaxErrorException("Only one frontmatter section is allowed; remove the extra lines beginning with ---.");
        }
        list($decoded['data'], $decoded['body']) = $lines;

        $decoded['data'] = Yaml::parse($decoded['data']);
      }
    }
    else {
      $decoded['body'] = implode(PHP_EOL, $lines);
    }

    if (!preg_match('/^\s*#([^#].+)\n\s*(.*)/', $decoded['body'], $matches)) {
      throw new SyntaxErrorException("The body must being with a heading designated with a single hash (#) followed by a string of text.");
    }
    $decoded['title'] = trim($matches[1]);
    $decoded['body'] = trim($matches[2]);

    return $decoded;
  }

}
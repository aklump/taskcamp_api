<?php

namespace AKlump\Taskcamp\API;

use AKlump\Taskcamp\API\Helpers\ExplodeDocument;
use AKlump\Taskcamp\API\Helpers\ExtractTitle;
use AKlump\Taskcamp\API\Helpers\ParseElement;
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
   * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
   */
  public function encode(mixed $data, string $format, array $context = []): string {
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
    $lines[] = "<{$data['type']}$properties$self_closing>";

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
  public function supportsEncoding(string $format): bool {
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
   * @return array
   *
   * @throws \AKlump\Taskcamp\API\SyntaxErrorException
   * @inheritdoc
   */
  public function decode(string $data, string $format, array $context = []) {
    $doc = (new ExplodeDocument())($data);
    if (empty($doc[EntityInterface::ELEMENT])) {
      throw new SyntaxErrorException("The document is missing the XML self-closing element.");
    }
    $decoded['body'] = $doc[EntityInterface::CONTENT];
    $decoded['title'] = (new ExtractTitle())($decoded['body']);
    if (empty($decoded['title'])) {
      throw new SyntaxErrorException("The body must contain one heading designated with a single hash (#) followed by a string of text.");
    }

    $element = (new ParseElement())($doc[EntityInterface::ELEMENT]);
    $decoded['type'] = $element->name;
    $decoded['properties'] = $element->attributes;

    $decoded['data'] = $doc[EntityInterface::DATA] ? Yaml::parse($doc[EntityInterface::DATA]) : [];

    return $decoded;
  }

}

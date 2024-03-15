<?php

namespace AKlump\Taskcamp\API\Helpers;

use DOMDocument;
use InvalidArgumentException;
use stdClass;

class ParseElement {

  /**
   * Parse an element section into name and attributes.
   *
   * @param string $element
   *
   * @return \stdClass
   *   - name string The tag name.
   *   - attributes array Any tag key/value attributes.
   *
   * @see \AKlump\Taskcamp\API\EntityEncoder::ELEMENT
   */
  public function __invoke(string $element): stdClass {
    if (empty($element)) {
      throw new InvalidArgumentException('$element is empty.');
    }
    $doc = new DOMDocument();
    $is_standard = @$doc->loadXML($element);
    if (!$is_standard) {
      $element = $this->sanitize($element);
      $is_standard = @$doc->loadXML($element);
    }
    if (!$is_standard) {
      throw new InvalidArgumentException(sprintf('Invalid $element: %s', $element));
    }
    $tag = $doc->documentElement;
    $name = $tag->tagName;
    $attributes = [];
    if ($tag->hasAttributes()) {
      foreach ($tag->attributes as $attr) {
        $value = $attr->nodeValue;
        if (is_numeric($value)) {
          $value *= 1;
        }
        $attributes[$attr->nodeName] = $value;
      }
    }

    return (object) ['name' => $name, 'attributes' => $attributes];
  }

  /**
   * Do not call if $element is a standard XML element.
   *
   * @param string $element
   *   A non-standard XML element.
   *
   * @return array|string|string[]|null
   */
  private function sanitize(string $element) {
    // Convert to self-closing tag.
    $element = preg_replace('#(?<!/)>$#', '/>', $element);

    // Quote all attributes
    if (str_contains($element, '=')) {
      $element = $this->quoteAttributes($element);
    }

    return $element;
  }

  private function quoteAttributes(string $element) {
    $replaceAll = array(
      '/<(\w+)\s+([^>]*[^"])(\w+)=(\w+)([^"]*[^>])/' => '<$1 $2$3="$4"$5',
    );

    return preg_replace(array_keys($replaceAll), array_values($replaceAll), $element);
  }
}

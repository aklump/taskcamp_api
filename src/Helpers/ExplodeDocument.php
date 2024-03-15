<?php

namespace AKlump\Taskcamp\API\Helpers;

use AKlump\Taskcamp\API\EntityInterface;

class ExplodeDocument {

  /**
   * Explode a single document into it's three major string components.
   *
   * @param string $document
   *
   * @return string[]
   *   EntityInterface::ELEMENT => string Whitespace trimmed.
   *   EntityInterface::DATA => string Whitespace trimmed.
   *   EntityInterface::CONTENT => string Whitespace not trimmed.
   */
  public function __invoke(string $document): array {
    $result = array_fill_keys([
      EntityInterface::ELEMENT,
      EntityInterface::DATA,
      EntityInterface::CONTENT,
    ], '');
    $sections = $this->getSections($document);
    while ($section = array_shift($sections)) {
      $this->assignSection($result, $section);
    }

    return $result;
  }

  private function getSections(string $document) {
    $sections = preg_split('#^\-{3,}\n#m', $document, 3);
    $sections = array_values(array_filter($sections));
    if (count($sections) === 1) {
      $sections = explode("\n", $sections[0], 2);
    }

    return $sections;
  }

  private function assignSection(array &$result, string $section) {
    $type = (new GuessSectionType())($section);
    if ($type !== EntityInterface::CONTENT) {
      $section = trim($section);
    }
    $result[$type] = $section;
  }
}

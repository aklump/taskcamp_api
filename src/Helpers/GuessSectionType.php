<?php

namespace AKlump\Taskcamp\API\Helpers;

use AKlump\Taskcamp\API\EntityInterface;
use AKlump\Taskcamp\API\SyntaxErrorException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class GuessSectionType {

  /**
   * Give a string section guess it's type.
   *
   * @param string $section
   *
   * @return string
   * @throws \AKlump\Taskcamp\API\SyntaxErrorException
   *
   * @see \AKlump\Taskcamp\API\Helpers\ExplodeDocument
   */
  public function __invoke(string $section): string {
    if ($this->isElement($section)) {
      return EntityInterface::ELEMENT;
    }
    if ($this->isData($section)) {
      return EntityInterface::DATA;
    }

    $error = $this->isSyntaxError($section);
    if ($error) {
      return throw new SyntaxErrorException(sprintf($error, $section));
    }

    return EntityInterface::CONTENT;
  }

  private function isElement(string $section) {
    try {
      $element = (new ParseElement())($section);

      return !empty($element->name);
    }
    catch (\InvalidArgumentException $exception) {
      return FALSE;
    }
  }

  private function isData(string $section) {
    try {
      $data = Yaml::parse($section);

      return is_array($data);
    }
    catch (ParseException $exception) {
      return FALSE;
    }
  }

  private function isSyntaxError(string $section): string {
    if (preg_match('#^\s*<\w+#', $section)) {
      return 'The element appears incorrect: %s';
    }

    return '';
  }
}

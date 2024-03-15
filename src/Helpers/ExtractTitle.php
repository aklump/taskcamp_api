<?php

namespace AKlump\Taskcamp\API\Helpers;

class ExtractTitle {

  /**
   * Remove the first h1 title from $content and return it, e.g., "# Title\n")
   *
   * @param string $content
   *
   * @return string
   */
  public function __invoke(string &$content): string {
    if (empty($content)) {
      return '';
    }
    if (!preg_match('/^#\s*(?!#)(.+)\n?/m', $content, $matches)) {
      return '';
    }

    $title = $matches[1] ?? '';

    // Replace just the first occurrence of title in $content.
    $content = preg_replace('/' . preg_quote($matches[0], '/') . '/', '', $content, 1);
    $content = ltrim($content);

    return $title;
  }

}

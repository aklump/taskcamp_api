<?php

namespace AKlump\Taskcamp\API\Tests\Unit\Helpers;

use AKlump\Taskcamp\API\EntityInterface;
use AKlump\Taskcamp\API\Helpers\GuessSectionType;
use AKlump\Taskcamp\API\SyntaxErrorException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\Helpers\GuessSectionType
 * @uses   \AKlump\Taskcamp\API\Helpers\ParseElement
 */
final class GuessSectionTypeTest extends TestCase {

  public function dataFortestInvokeProvider() {
    $tests = [];
    $tests[] = [
      'key: value1: value2',
      EntityInterface::CONTENT,
    ];
    $tests[] = [
      "# Lorem Ipsum",
      EntityInterface::CONTENT,
    ];
    $tests[] = [
      "lorem: ipsum\ndolar: sit\n",
      EntityInterface::DATA,
    ];
    $tests[] = [
      '<bug>',
      EntityInterface::ELEMENT,
    ];
    $tests[] = [
      '<bug foo="bar">',
      EntityInterface::ELEMENT,
    ];
    $tests[] = [
      '<bug foo=9>',
      EntityInterface::ELEMENT,
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestInvokeProvider
   */
  public function testInvoke(string $section, string $expected) {
    $type = (new GuessSectionType())($section);
    $this->assertSame($expected, $type);
  }

  public function testInvokeThrowsForSyntaxProblems() {
    $string = '<bug name=Rin Tin/>\n# My Pets';
    $this->expectException(SyntaxErrorException::class);
    $this->expectExceptionMessageMatches('#' . preg_quote($string) . '#');
    (new GuessSectionType())($string);
  }
}

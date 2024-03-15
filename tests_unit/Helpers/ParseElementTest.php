<?php

namespace AKlump\Taskcamp\API\Tests\Unit\Helpers;

use AKlump\Taskcamp\API\Helpers\ParseElement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\Helpers\ParseElement
 */
final class ParseElementTest extends TestCase {

  public function _testInvokeWithoutQuotes() {
    $string = "<bug projectId=0d6c69da-3167-11e9-901e-69eb3dc3eca4/>";
    $element = (new ParseElement())($string);

    $this->assertSame('bug', $element->name);
    $this->assertCount(1, $element->attributes);
    $this->assertSame('0d6c69da-3167-11e9-901e-69eb3dc3eca4', $element->attributes['projectId']);
  }

  public function dataFortestInvokeVariousStringsProvider() {
    $tests = [];
    $tests[] = [
      "<bug group=6>",
      ['group' => 6],
    ];
    $tests[] = [
      "<bug group=6a>",
      ['group' => '6a'],
    ];
    $tests[] = [
      "<bug>",
      [],
    ];
    $tests[] = [
      "<bug/>",
      [],
    ];
    $tests[] = [
      "<bug projectId='0d6c69da-3167-11e9-901e-69eb3dc3eca4'/>",
      [
        'projectId' => '0d6c69da-3167-11e9-901e-69eb3dc3eca4',
      ],
    ];
    $tests[] = [
      '<bug projectId="0d6c69da-3167-11e9-901e-69eb3dc3eca4"/>',
      [
        'projectId' => '0d6c69da-3167-11e9-901e-69eb3dc3eca4',
      ],
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestInvokeVariousStringsProvider
   */
  public function testInvokeVariousStrings(string $string, array $expected_attr) {
    $element = (new ParseElement())($string);
    $this->assertSame('bug', $element->name);
    $this->assertCount(count($expected_attr), $element->attributes);
    foreach ($expected_attr as $key => $value) {
      $this->assertSame($value, $element->attributes[$key]);
    }
  }

  public function testNonElementThrows() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('#foobar#');
    (new ParseElement())('foobar');
  }

  public function testEmptyElementThrows() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('#empty#');
    (new ParseElement())('');
  }

  public function testValueErrorThrows() {
    $this->expectException(\InvalidArgumentException::class);
    (new ParseElement())('<root><unclosedTag>');
  }
}

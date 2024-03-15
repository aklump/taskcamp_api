<?php

namespace AKlump\Taskcamp\API\Tests\Unit\Helpers;

use AKlump\Taskcamp\API\Helpers\ExtractTitle;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\Helpers\ExtractTitle
 */
final class ExtractTitleTest extends TestCase {

  public function dataFortestInvokeProvider() {
    $tests = [];
    $tests[] = [
      "#Title\n\n\nLorem\n\n",
      "Title",
      "Lorem\n\n",
    ];
    $tests[] = [
      "#Title\n\n\n\n",
      "Title",
      "",
    ];
    $tests[] = [
      "foo",
      "",
      "foo",
    ];
    $tests[] = [
      "lorem\n# Foo\nipsum\n",
      "Foo",
      "lorem\nipsum\n",
    ];
    $tests[] = [
      "# Foo\n# Foo",
      'Foo',
      '# Foo',
    ];
    $tests[] = [
      '',
      '',
      '',
    ];
    $tests[] = [
      '#foo',
      'foo',
      '',
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestInvokeProvider
   */
  public function testInvoke($content, $title, $body) {
    $result = (new ExtractTitle())($content);
    $this->assertSame($title, $result);
    $this->assertSame($body, $content);
  }
}

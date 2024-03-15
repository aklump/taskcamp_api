<?php

namespace AKlump\Taskcamp\API\Tests\Unit\Helpers;

use AKlump\Taskcamp\API\EntityInterface;
use AKlump\Taskcamp\API\Helpers\ExplodeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\Helpers\ExplodeDocument
 * @uses   \AKlump\Taskcamp\API\Helpers\GuessSectionType
 * @uses   \AKlump\Taskcamp\API\Helpers\ParseElement
 */
final class ExplodeDocumentTest extends TestCase {

  public function dataFortestInvokeProvider() {
    $tests = [];

    $tests[] = [
      "<foo/>\nbir: biz\ncar: caz\n---\n",
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => "bir: biz\ncar: caz",
        EntityInterface::CONTENT => '',
      ],
    ];

    return $tests;

    // The element MUST be present and MUST be contained on a single line
    $tests[] = [
      '<foo/>',
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => '',
      ],
    ];
    // The element MAY have one or more attribute/values; the values MAY be wrapped in double ... quotes. If an attribute value contains a space, it MUST be wrapper in single or double quotes.
    $tests[] = [
      '<foo id="bar bar"/>',
      [
        EntityInterface::ELEMENT => '<foo id="bar bar"/>',
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => '',
      ],
    ];
    // The element MAY have one or more attribute/values; the values MAY be wrapped in ... single quotes. If an attribute value contains a space, it MUST be wrapper in single or double quotes.
    $tests[] = [
      "<foo id='bar bar'/>",
      [
        EntityInterface::ELEMENT => "<foo id='bar bar'/>",
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => '',
      ],
    ];
    // The element MAY have one or more attribute/values ...
    $tests[] = [
      "<foo id=bar/>",
      [
        EntityInterface::ELEMENT => "<foo id=bar/>",
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => '',
      ],
    ];

    // The YAML frontmatter MUST end with 3 or more - characters.
    $tests[] = [
      "<foo/>\n---\n# Lorem Ipsum\n\n",
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => "# Lorem Ipsum\n\n",
      ],
    ];

    // The YAML frontmatter MAY begin with 3 or more - characters, or this line MAY be omitted.
    $tests[] = [
      "<foo/>\nbir: biz\ncar: caz\n---\n",
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => "bir: biz\ncar: caz",
        EntityInterface::CONTENT => '',
      ],
    ];
    $tests[] = [
      "<foo/>\n---\nbar: baz\n---\n",
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => 'bar: baz',
        EntityInterface::CONTENT => '',
      ],
    ];
    $tests[] = [
      "<foo/>\n---\nbar: baz\n---\nLorem\nIpsum\n",
      [
        EntityInterface::ELEMENT => '<foo/>',
        EntityInterface::DATA => 'bar: baz',
        EntityInterface::CONTENT => "Lorem\nIpsum\n",
      ],
    ];
    $tests[] = [
      '',
      [
        EntityInterface::ELEMENT => '',
        EntityInterface::DATA => '',
        EntityInterface::CONTENT => '',
      ],
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestInvokeProvider
   */
  public function testInvoke(string $document, array $expected) {
    $result = (new ExplodeDocument())($document);
    $this->assertCount(3, $result);
    $this->assertSame($expected, $result);
  }

  public function testInvokeDoesNotTrimWhitespaceFromContent() {
    $document = "<foo/>\n# Lorem Ipsum\n\n";
    $result = (new ExplodeDocument())($document);
    $this->assertSame("# Lorem Ipsum\n\n", $result[EntityInterface::CONTENT]);
  }
}

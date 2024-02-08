<?php

use AKlump\Taskcamp\API\EntityEncoder;
use AKlump\Taskcamp\API\SyntaxErrorException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\EntityEncoder
 */
final class DecodeTest extends TestCase {

  /**
   * Provides data for testDecodeTypeWorksReturnsArrayForVariations.
   */
  public static function serializedDataProvider() {
    $tests = [];

    $tests[] = [
      "<bug>\n---\nfoo: bar\n---\n# Title\n\n## Subtitle\n---\n\nlorem ipsum.",
      [
        'type' => 'bug',
        'title' => 'Title',
        'body' => "## Subtitle\n---\n\nlorem ipsum.",
        'data' => ['foo' => 'bar'],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "\n\n\n<bug>\n---\n---\n#The Beginning\n\n\n\n",
      [
        'type' => 'bug',
        'title' => 'The Beginning',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "<person age=47>\n# Dr. Smith",
      [
        'type' => 'person',
        'title' => 'Dr. Smith',
        'body' => '',
        'data' => [],
        'properties' => ['age' => 47],
      ],
    ];

    $tests[] = [
      "<feature>\n#Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n## Checklist\n\n- do this\n- do that\n\n## Subheader\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.  ",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n## Checklist\n\n- do this\n- do that\n\n## Subheader\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        'data' => [],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "<feature>\n#Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'data' => [],
        'properties' => [],
      ],
    ];
    $tests[] = [
      "<feature id=123>\n---\ncolor: red\nsize: large\n---\n#Title\n\n\n\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [
          'color' => 'red',
          'size' => 'large',
        ],
        'properties' => ['id' => 123],
      ],
    ];
    $tests[] = [
      "<feature id=\"step up\">\n---\ncolor: red\nsize: large\n---\n#Title\n\n\n\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [
          'color' => 'red',
          'size' => 'large',
        ],
        'properties' => ['id' => 'step up'],
      ],
    ];


    $tests[] = [
      "<feature id=\"123\">\n---\n---\n#Title\n\n\n\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => ['id' => 123],
      ],
    ];
    $tests[] = [
      "<feature>\n---\n---\n#Title\n\n\n\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];
    $tests[] = [
      "<feature>\n---\n---\n#Title",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];
    $tests[] = [
      "<feature>\n---\n#Title",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];
    $tests[] = [
      "<feature>\n#Title",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];

    return $tests;
  }

  /**
   * @dataProvider serializedDataProvider
   */
  public function testDecodeTypeWorksReturnsArrayForVariations($serialized_data, $control) {
    $obj = new EntityEncoder();
    $data = $obj->decode($serialized_data, 'taskcamp_entity');
    $this->assertIsArray($data);
    foreach (array_keys($control) as $key) {
      $this->assertSame($control[$key], $data[$key]);
    }
  }

  public function testUnquotedSpaceOneProperyThrows() {
    $this->expectException(SyntaxErrorException::class);
    $serialized_data = "<bug name=Rin Tin/>\n# My Pets";
    $obj = new EntityEncoder();
    $obj->decode($serialized_data, 'taskcamp_entity');
  }

  public function testUnquotedSpacePropertiesThrows() {
    $this->expectException(SyntaxErrorException::class);
    $serialized_data = "<bug name=Rin Tin city=Kansas City>\n# My Pets";
    $obj = new EntityEncoder();
    $obj->decode($serialized_data, 'taskcamp_entity');
  }

  public function testMissingHeaderThrows() {
    $this->expectException(SyntaxErrorException::class);
    $serialized_data = "# Title";
    $obj = new EntityEncoder();
    $obj->decode($serialized_data, 'taskcamp_entity');
  }

  public function testHeaderOnlyThrows() {
    $this->expectException(SyntaxErrorException::class);
    $serialized_data = "<bug>";
    $obj = new EntityEncoder();
    $obj->decode($serialized_data, 'taskcamp_entity');
  }

  public function testMissingTitleThrows() {
    $this->expectException(SyntaxErrorException::class);
    $serialized_data = "<bug>\n## No title";
    $obj = new EntityEncoder();
    $obj->decode($serialized_data, 'taskcamp_entity');
  }

}

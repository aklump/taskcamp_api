<?php

use AKlump\Taskcamp\API\EntityEncoder;
use AKlump\Taskcamp\API\SyntaxErrorException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\EntityEncoder
 */
final class EncodeTest extends TestCase {

  /**
   * Provides data for testDecodeTypeWorksReturnsArrayForVariations.
   */
  public static function serializedDataProvider() {
    $tests = [];

    $tests[] = [
      "<feature/>\n# Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n",
      "<feature>\n# Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'data' => [],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "<feature id=\"123\"/>\n---\ncolor: red\nsize: large\n---\n# Title\n",
      "<feature id=123>\ncolor: red\nsize: large\n---\n# Title\n",
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
      "<feature id=\"123\"/>\n# Title\n",
      "<feature id=123>\n# Title\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => ['id' => 123],
      ],
    ];

    $tests[] = [
      "<feature id=\"step up\"/>\n# Title\n",
      "<feature id=\"step up\">\n# Title\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => ['id' => 'step up'],
      ],
    ];

    $tests[] = [
      "<feature/>\n# Title\n",
      "<feature>\n# Title\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "<bug/>\n# The Beginning\n",
      "<bug>\n# The Beginning\n",
      [
        'type' => 'bug',
        'title' => 'The Beginning',
        'body' => '',
        'data' => [],
        'properties' => [],
      ],
    ];

    $tests[] = [
      "<person age=\"47\"/>\n# Dr. Smith\n",
      "<person age=47>\n# Dr. Smith\n",
      [
        'type' => 'person',
        'title' => 'Dr. Smith',
        'body' => '',
        'data' => [],
        'properties' => ['age' => 47],
      ],
    ];

    $tests[] = [
      "<feature/>\n# Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n## Checklist\n\n- do this\n- do that\n\n## Subheader\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n",
      "<feature>\n# Title\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n## Checklist\n\n- do this\n- do that\n\n## Subheader\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n",
      [
        'type' => 'feature',
        'title' => 'Title',
        'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n## Checklist\n\n- do this\n- do that\n\n## Subheader\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        'data' => [],
        'properties' => [],
      ],
    ];

    return $tests;
  }

  /**
   * @dataProvider serializedDataProvider
   */
  public function testDecodeTypeWorksReturnsArrayForVariations($control, $relaxed_control, $data) {
    $obj = new EntityEncoder();
    $serialized_data = $obj->encode($data, 'taskcamp_entity');
    $this->assertSame($control, $serialized_data);

    $serialized_data = $obj->encode($data, 'taskcamp_entity', ['mode' => 'strict']);
    $this->assertSame($control, $serialized_data);

    $serialized_data = $obj->encode($data, 'taskcamp_entity', ['mode' => 'relaxed']);
    $this->assertSame($relaxed_control, $serialized_data);
  }

  public function testEncodeMissingTypeThrows() {
    $this->expectException(SyntaxErrorException::class);
    $obj = new EntityEncoder();
    $this->assertIsString($obj->encode([], ''));
  }
}

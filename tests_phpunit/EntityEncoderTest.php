<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\EntityEncoder
 */
final class EntityEncoderTest extends TestCase {

  /**
   * Provides data for testDecodeTypeWorksReturnsArrayForVariations.
   */
  public function serializedDataProvider() {
    $tests = [];

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
    $obj = new \AKlump\Taskcamp\API\EntityEncoder();
    $data = $obj->decode($serialized_data, 'taskcamp_entity');
    $this->assertIsArray($data);
    foreach (array_keys($control) as $key) {
      $this->assertSame($control[$key], $data[$key]);
    }
  }

  public function testEncodeMissingTypeThrows() {
    $this->expectException(\AKlump\Taskcamp\API\SyntaxErrorException::class);
    $obj = new \AKlump\Taskcamp\API\EntityEncoder();
    $this->assertIsString($obj->encode([], ''));
  }
}

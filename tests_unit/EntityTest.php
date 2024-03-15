<?php

namespace AKlump\Taskcamp\API\Tests\Unit;

use AKlump\Taskcamp\API\Entity;

/**
 * @covers \AKlump\Taskcamp\API\Entity
 */
class EntityTest extends \PHPUnit\Framework\TestCase {

  public function testJsonSerialize() {
    $entity = (new Entity())->setTitle('Lorem')
      ->setType('feature')
      ->setBody('Do re me.')
      ->setProperties(['id' => 'lorem'])
      ->setData(['uri' => 'private://files']);
    $expected = '{"title":"Lorem","type":"feature","body":"Do re me.","properties":{"id":"lorem"},"data":{"uri":"private:\/\/files"}}';
    $this->assertSame($expected, json_encode($entity));
  }

  public function testData() {
    $entity = (new Entity());
    $type = ['z' => 'zulu', 'y' => 'yankee'];
    $result = $entity->setData($type)->getData();
    $this->assertSame($type, $result);
    $this->assertSame('zulu', $entity->getDatum('z', NULL));
  }

  public function testType() {
    $type = 'foo';
    $result = (new Entity())->setType($type)->getType();
    $this->assertSame($type, $result);
  }

  public function testProperties() {
    $entity = (new Entity());
    $type = ['a' => 'apple', 'b' => 'banana'];
    $result = $entity->setProperties($type)->getProperties();
    $this->assertSame($type, $result);
    $this->assertSame('apple', $entity->getProperty('a', NULL));
  }

  public function testTitle() {
    $type = 'Lorem Ipsum';
    $result = (new Entity())->setTitle($type)->getTitle();
    $this->assertSame($type, $result);
  }

  public function testBody() {
    $type = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $result = (new Entity())->setBody($type)->getBody();
    $this->assertSame($type, $result);
  }
}

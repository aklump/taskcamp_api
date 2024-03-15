<?php

namespace AKlump\Taskcamp\API\Tests\Unit;

use AKlump\Taskcamp\API\Entity;
use AKlump\Taskcamp\API\EntityEncoder;
use AKlump\Taskcamp\API\EntitySerializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Taskcamp\API\EntitySerializer
 * @uses   \AKlump\Taskcamp\API\Entity
 * @uses   \AKlump\Taskcamp\API\EntityEncoder
 * @uses   \AKlump\Taskcamp\API\Helpers\GuessSectionType
 * @uses   \AKlump\Taskcamp\API\Helpers\ParseElement
 * @uses   \AKlump\Taskcamp\API\Helpers\ExplodeDocument
 * @uses   \AKlump\Taskcamp\API\Helpers\ExtractTitle
 */
class EntitySerializerTest extends TestCase {

  public function testDeserializeTaskcampEntityType() {
    $json = '<feature projectId="123"/>
---
priority: high
---
# Augment the footer with another section
';
    $serializer = new EntitySerializer();
    $entity = $serializer->deserialize($json, Entity::class, EntityEncoder::TYPE);
    $this->assertSame('feature', $entity->getType());
    $this->assertSame(123, $entity->getProperty('projectId', NULL));
    $this->assertSame('Augment the footer with another section', $entity->getTitle());
    $this->assertSame('', $entity->getBody());
    $this->assertSame('high', $entity->getDatum('priority', 'normal'));
  }

  public function testDeserializeXml() {
    $json = '<entity><type>bug</type><title>Lorem title</title></entity>';
    $serializer = new EntitySerializer();
    $entity = $serializer->deserialize($json, Entity::class, 'xml');
    $this->assertSame('bug', $entity->getType());
    $this->assertSame('Lorem title', $entity->getTitle());
    $this->assertSame('', $entity->getBody());
    $this->assertSame([], $entity->getData());
  }

  public function testDeserializeJson() {
    $json = '{"type":"bug","properties":{"projectName":"My Project","projectId":123},"data":{"device":"mac","os":{"name":"macosx","version":"10.13.6"}},"title":"The title has too much top margin","body":""}';

    $serializer = new EntitySerializer();
    $entity = $serializer->deserialize($json, Entity::class, 'json');
    $this->assertSame('bug', $entity->getType());
    $this->assertSame('The title has too much top margin', $entity->getTitle());
    $this->assertSame('', $entity->getBody());
    $this->assertSame('My Project', $entity->getProperty('projectName', ''));
    $this->assertSame(123, $entity->getProperty('projectId', NULL));
    $this->assertSame([
      'device' => 'mac',
      'os' => [
        'name' => 'macosx',
        'version' => '10.13.6',
      ],
    ], $entity->getData());
  }

  public function testSerialize() {
    $entity = new Entity();
    $entity
      ->setType('feature')
      ->setTitle('Augment the footer with another section')
      ->setBody('In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.')
      ->setProperties(['projectId' => 123])
      ->setData(['priority' => 'high']);
    $serializer = new EntitySerializer();
    $markup = $serializer->serialize($entity, EntityEncoder::TYPE);
    $this->assertSame('<feature projectId="123"/>
---
priority: high
---
# Augment the footer with another section

In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.
', $markup);
  }
}

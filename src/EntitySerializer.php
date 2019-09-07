<?php

namespace AKlump\Taskcamp\API;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * A configured serializer class for Entity.
 */
class EntitySerializer extends Serializer {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    parent::__construct([new GetSetMethodNormalizer()], [
      new EntityEncoder(),
      new JsonEncoder(),
      new XmlEncoder(),
    ]);
  }

}

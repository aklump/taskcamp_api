<?php

namespace AKlump\Taskcamp\API;

/**
 * Interface EntityInterface.
 *
 * @package AKlump\Taskcamp\API
 */
interface EntityInterface {

  public function getBody(): string;

  public function getData(): array;

  public function getDatum(string $datum, $default);

  public function getProperties(): array;

  public function getProperty(string $property, $default);

  public function getTitle(): string;

  public function getType(): string;

  public function setBody(string $body): EntityInterface;

  public function setData(array $data): EntityInterface;

  public function setProperties(array $properties): EntityInterface;

  public function setTitle(string $title): EntityInterface;

  public function setType(string $type): EntityInterface;

}

<?php

namespace AKlump\Taskcamp\API;

/**
 * Interface ObjectInterface.
 *
 * @package AKlump\Taskcamp\API
 */
interface ObjectInterface {

  public function getBody(): string;

  public function getData(): array;

  public function getProperties(): array;

  public function getTitle(): string;

  public function getType(): string;

  public function setBody(string $body): ObjectInterface;

  public function setData(array $data): ObjectInterface;

  public function setProperties(array $properties): ObjectInterface;

  public function setTitle(string $title): ObjectInterface;

  public function setType(string $type): ObjectInterface;

}

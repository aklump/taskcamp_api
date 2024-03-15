<?php

namespace AKlump\Taskcamp\API;

class Entity implements EntityInterface, \JsonSerializable {

  protected $data = [];

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return $this->data['type'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties(): array {
    return $this->data['properties'] ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getData(): array {
    return $this->data['data'] ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->data['title'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getBody(): string {
    return $this->data['body'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function setType(string $type): EntityInterface {
    $this->data['type'] = $type;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setProperties(array $properties): EntityInterface {
    $this->data['properties'] = $properties;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setData(array $data): EntityInterface {
    $this->data['data'] = $data;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle(string $title): EntityInterface {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setBody(string $body): EntityInterface {
    $this->data['body'] = $body;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function jsonSerialize() {
    return $this->data;
  }

  /**
   * {@inheritdoc}
   */
  public function getDatum(string $datum, $default) {
    return $this->getData()[$datum] ?? $default;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperty(string $property, $default) {
    return $this->getProperties()[$property] ?? $default;
  }

}

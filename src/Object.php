<?php

namespace AKlump\Taskcamp\Api;

class Object implements ObjectInterface {

  protected $data = [];

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return $this->data['type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties(): array {
    return $this->data['properties'];
  }

  /**
   * {@inheritdoc}
   */
  public function getData(): array {
    return $this->data['data'];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->data['title'];
  }

  /**
   * {@inheritdoc}
   */
  public function getBody(): string {
    return $this->data['body'];
  }

  /**
   * {@inheritdoc}
   */
  public function setType(string $type): ObjectInterface {
    $this->data['type'] = $type;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setProperties(array $properties): ObjectInterface {
    $this->data['properties'] = $properties;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setData(array $data): ObjectInterface {
    $this->data['data'] = $data;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle(string $title): ObjectInterface {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setBody(string $body): ObjectInterface {
    $this->data['body'] = $body;

    return $this;
  }

}

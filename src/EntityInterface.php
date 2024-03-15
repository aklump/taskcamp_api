<?php

namespace AKlump\Taskcamp\API;

/**
 * Interface EntityInterface.
 */
interface EntityInterface {

  const ELEMENT = 'element';

  const DATA = 'data';

  const CONTENT = 'content';

  /**
   * Get the body portion of the entity.
   *
   * @return string
   *   The body.
   */
  public function getBody(): string;

  /**
   * Get the data associated with the entity.
   *
   * @return array
   *   All existing data.
   */
  public function getData(): array;

  /**
   * Get a datum value or default value.
   *
   * @param string $datum
   *   The data key.
   * @param $default
   *   The value to use if $datum does not exist.
   *
   * @return mixed
   *   The value or default value.
   */
  public function getDatum(string $datum, $default);

  public function getProperties(): array;

  /**
   * Get the value of a property or default if property does not exist.
   *
   * @param string $property
   *   The name of the property.
   * @param $default
   *   The default value if property does not exist.
   *
   * @return mixed
   *   The value or default value.
   */
  public function getProperty(string $property, $default);

  /**
   * Get the title of the entity.
   *
   * @return string
   *   The title of the entity.
   */
  public function getTitle(): string;

  /**
   * Get the entity type.
   *
   * @return string
   *   The entity type.
   */
  public function getType(): string;

  public function setBody(string $body): EntityInterface;

  public function setData(array $data): EntityInterface;

  public function setProperties(array $properties): EntityInterface;

  public function setTitle(string $title): EntityInterface;

  public function setType(string $type): EntityInterface;

}

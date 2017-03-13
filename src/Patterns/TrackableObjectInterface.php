<?php

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Interface for trackable objects.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @internal
 */
interface TrackableObjectInterface {

  /**
   * Returns trackable object ID.
   * 
   * @since 1.0.0
   *
   * @return string
   */
  public function getObjectID();

  /**
   * Returns trackable object type.
   * 
   * @since 1.0.0
   *
   * @return string
   */
  public function getObjectType();
}
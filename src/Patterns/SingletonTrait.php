<?php

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Set of traits for a Singleton containing a public __construct
 *
 * @package Bebop\Common
 * @since 1.1.5
 * @api
 */
trait SingletonTrait {

  /**
   * Class instance.
   * 
   * @since 1.1.5
   *
   * @var object
   */
  private static $instance;

  /**
   * Do not allow clones.
   * 
   * @since 1.1.5
   *
   * @return void
   */
  private final function __clone() {}

  /**
   * Gets single instance of called class.
   * 
   * @since 1.1.5
   *
   * @return object
   */
  public static function getInstance() 
  {
    if ( ! isset( static::$instance ) )
      static::$instance = new static;

    return static::$instance;
  }
}
<?php
/**
 * Feature class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;

/**
 * Single feature configuration
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @api
 */
class Feature {

  /**
   * Featured ID
   * 
   * @since 1.0.0
   *
   * @var string
   */
  protected $id;

  /**
   * Feature configuration object
   * 
   * @since 1.0.0
   *
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $config;

  /**
   * Enabled status
   * 
   * @since 1.0.0
   *
   * @var boolean
   */
  protected $enabled = false;

  /**
   * Instantiates feature
   * 
   * @since 1.0.0
   *
   * @param string $id Feature ID
   * @param array $config Feature configuration array
   */
  public function __construct( $id, array $config = [])
  {
    if ( ! is_string( $id ) )
      throw new \Exception( "Feature id must be a string" );

    $this->id     = $id;
    $this->config = new Collection( $config );
  }

  /**
   * Enables feature
   * 
   * @since 1.0.0
   *
   * @return Feature This class instance
   */
  public function enable()
  {
    $this->enabled = true;

    return $this;
  }

  /**
   * Disables feature
   * 
   * @since 1.0.0
   *
   * @return Feature This class instance
   */
  public function disable()
  {
    $this->enabled = false;

    return $this;
  }

  /**
   * Checks if feature is enabled
   * 
   * @since 1.0.0
   *
   * @return boolean True is enabled, false otherwise
   */
  public function isEnabled()
  {
    return $this->enabled;
  }

  /**
   * Returns featued ID
   * 
   * @since 1.0.0
   *
   * @return string Feature ID
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Sets a single configuration value
   * 
   * @since 1.0.0
   *
   * @param string $key Configuration key
   * @param mixed $value Configuration value
   * @return Feature This class instance
   */
  public function set( $key, $value )
  {
    if ( is_string( $key ) )
      $this->config->set( $key, $value );

    return $this;
  }

  /**
   * Returns a single configuration value
   * 
   * @since 1.0.0
   *
   * @param string $key Configuration key
   * @return mixed $value Configuration value
   */
  public function get( $key )
  {
    return is_string( $key ) ? $this->config->get( $key ) : null;
  }

  /**
   * Checks if the target configuration key exists
   * 
   * @since 1.0.0
   *
   * @param string $key Configuration key
   * @return boolean True if exists, false otherwise
   */
  public function has( $key )
  {
    return is_string( $key ) ? $this->config->hasKey( $key ) : false;
  }

  /**
   * Get the full configuration array
   * 
   * @since 1.0.0
   *
   * @return array Full configuration array
   */
  public function getAll()
  {
    return $this->config->getAll();
  }
}
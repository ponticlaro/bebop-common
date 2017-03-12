<?php
/**
 * EnvManager class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;

/**
 * Determines the current environment.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @since 1.1.5 Uses Singleton trait
 * @api
 */
class EnvManager {

  use Singleton;

  /**
   * List of environments
   * 
   * @since 1.0.0
   *
   * @var array
   */
  protected $__envs = [];

  /**
	 * Instantiates class
	 * 
   * @since 1.0.0
	 */
  public function __construct()
  {
    // Instantiate environments collection object
    $this->add( 'development' )
         ->add( 'staging' )
         ->add( 'production' );
  }

  /**
   * Adds a new environment with target key, if we do not have that key already
   * 
   * @since 1.0.0
   *
   * @param string $key Key for the new environment
   * @return EnvManager This class instance
   */
  public function add( $key )
  {
    if ( ! is_string( $key ) || $this->exists( $key ) )
      return $this;

    $this->__envs[ $key ] = new Env( $key );

    return $this;
  }

  /**
   * Replaces an existing environment or adds a new one.
   * 
   * @since 1.0.0
   *
   * @param string $key Key of the environment to replace or add
   * @return EnvManager This class instance
   */
  public function replace( $key )
  {
    if ( ! is_string( $key ) ) 
      return $this;

    $this->__envs[ $key ] = new Env( $key );

    return $this;
  }

  /**
   * Checks if the target environment exists.
   * 
   * @since 1.0.0
   *
   * @param string $key Key of the environment to check
   * @return boolean True is exists, false otherwise
   */
  public function exists( $key )
  {
    if ( ! is_string( $key ) ) 
      return false;

    return isset( $this->__envs[ $key ] );
  }

  /**
   * Returns the target environment.
   * 
   * @since 1.0.0
   *
   * @param string $key Key of the environment to get
   * @return Env Env class instance
   */
  public function get( $key )
  {
    if ( ! is_string( $key ) ) 
      return null;

    if ( ! $this->exists( $key ) )
      return null;

    return $this->__envs[ $key ];
  }

  /**
   * Removes the target environment.
   * 
   * @since 1.0.0
   *
   * @param string $key Key of the environment to remove
   * @return EnvManager This class instance
   */
  public function remove( $key )
  {
    if ( ! is_string($key ) ) 
      return $this;

    unset( $this->__envs[ $key ] );

    return $this;
  }

  /**
   * Checks if the target environment is the current one.
   * 
   * @since 1.0.0
   *
   * @param string $key Key of the environment to check
   * @return boolean True if it is the current environment, false otherwise
   */
  public function is( $key )
  {
    if ( ! is_string( $key ) ) 
      return false;

    return $key == $this->getCurrentKey() ? true : false;
  }

  /**
   * Returns the current environment.
   * 
   * @since 1.0.0
   *
   * @return Ponticlaro\Bebop\Env The current environment
   */
  public function getCurrent()
  {
    foreach ( $this->__envs as $key => $env ) {
        
      if ( $env->isCurrent() ) 
        return $env;
    }

    // Making sure we have a development environment
    if ( ! $this->exists( 'development' ) )
      $this->add( 'development' );

    return $this->get( 'development' );
  }

  /**
   * Returns the key of the current environment.
   * 
   * @since 1.0.0
   *
   * @return string Key of the current environment
   */
  public function getCurrentKey()
  {
    return $this->getCurrent()->getKey();
  }
}
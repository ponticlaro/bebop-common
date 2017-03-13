<?php

namespace Ponticlaro\Bebop\Common;

/**
 * Callable wrapper used to determine the current environment.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @api
 */
class Env {

  /**
   * Key that identifies this environment.
   * 
   * @since 1.0.0
   *
   * @var string
   */
  private $__key;

  /**
   * List of environments.
   * 
   * @since 1.0.0
   *
   * @var array
   */
  private $__hosts = [];

  /**
   * Instantiates Env object.
   * 
   * @since 1.0.0
   */
  public function __construct( $key )
  {
    if ( ! is_string( $key ) )
      throw new \Exception( 'Ponticlaro\Bebop\Common\Env $key must be a string' );
    
    // Store key
    $this->__key = $key;
  }

  /**
   * Returns the key that identifies this environment.
   * 
   * @since 1.0.0
   *
   * @return string The key that identifies this environment
   */
  public function getKey()
  {
    return $this->__key;
  }

  /**
   * Adds a single host.
   * 
   * @since 1.0.0
   *
   * @param string $host Host to be added
   * @return Env This class instance
   */
  public function addHost( $host )
  {
    if ( is_string( $host ) ) 
      $this->__hosts[] = $host;

    return $this;
  }

  /**
   * Adds an array of hosts.
   * 
   * @since 1.0.0
   *
   * @param string $host Hosts to be added
   * @return Env This class instance
   */
  public function addHosts( array $hosts = [] )
  {
    foreach ( $hosts as $host ) {
  
      if ( is_string( $host ) ) 
        $this->__hosts[] = $host;
    }

    return $this;
  }

  /**
   * Returns all hosts.
   * 
   * @since 1.0.0
   *
   * @return array All hosts
   */
  public function getHosts()
  {
    return $this->__hosts;
  }

  /**
   * Checks if this environment have the target host.
   * 
   * @since 1.0.0
   *
   * @param string $host Host to check
   * @return boolean True if listed in this environment, false otherwise
   */
  public function hasHost( $host )
  {
    if ( ! is_string( $host ) )
      return false;

    $key = array_search( $host, $this->__hosts );

    return $key === false ? false : true;
  }

  /**
   * Checks if this is the current environment.
   * 
   * @since 1.0.0
   *
   * @return boolean True if listed in this environment, false otherwise
   */
  public function isCurrent()
  {   
    // Use Hosts to determine current environment
    if ( $this->__hosts ) {
        
      return $this->hasHost($_SERVER['SERVER_NAME']);
    }

    // Use APP_ENV to determine current environment
    elseif ( getenv( 'APP_ENV' ) && getenv( 'APP_ENV' ) == $this->__key ) {

      return true;
    }

    return false;
  }
}
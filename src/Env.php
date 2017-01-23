<?php

namespace Ponticlaro\Bebop\Common;

class Env {

  /**
   * Key that identifies this environment
   * 
   * @var string
   */
  private $__key;

  /**
   * List of environments
   * 
   * @var array;
   */
  private $__hosts = [];

  /**
   * Instantiates Env object
   * 
   */
  public function __construct($key)
  {
    if (!is_string($key))
      throw new \Exception('Ponticlaro\Bebop\Common\Env $key must be a string');
    
    // Store key
    $this->__key = $key;
  }

  /**
   * Returns the key that identifies this environment
   * 
   * @return string The key that identifies this environment
   */
  public function getKey()
  {
    return $this->__key;
  }

  /**
   * Adds a single host
   * 
   * @param string $host Host to be added
   */
  public function addHost($host)
  {
    if (is_string($host)) 
      $this->__hosts[] = $host;

    return $this;
  }

  /**
   * Adds an array of hosts
   * 
   * @param string $host Hosts to be added
   */
  public function addHosts(array $hosts = array())
  {
    foreach ($hosts as $host) {
  
      if (is_string($host)) 
        $this->__hosts[] = $host;
    }

    return $this;
  }

  /**
   * Returns all hosts
   * 
   * @return array All hosts
   */
  public function getHosts()
  {
    return $this->__hosts;
  }

  /**
   * Checks if this environment have the target host
   * 
   * @param  string  $host Host to check
   * @return boolean       True if listed in this environment, false otherwise
   */
  public function hasHost($host)
  {
    if (!is_string($host))
      return false;

    $key = array_search($host, $this->__hosts);

    return $key === false ? false : true;
  }

  /**
   * Checks if this is the current environment
   * 
   * @return boolean True if listed in this environment, false otherwise
   */
  public function isCurrent()
  {   
    // Use Hosts to determine current environment
    if ($this->__hosts) {
        
      return $this->hasHost($_SERVER['SERVER_NAME']);
    }

    // Use APP_ENV to determine current environment
    elseif (getenv('APP_ENV') && getenv('APP_ENV') == $this->__key) {

      return true;
    }

    return false;
  }
}
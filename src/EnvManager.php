<?php

namespace Ponticlaro\Bebop\Common;

class EnvManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

  /**
   * List of environments
   * 
   * @var array;
   */
  protected $__envs = [];

  /**
   * Instantiates Env Manager object
   * 
   */
  protected function __construct()
  {
    // Instantiate environments collection object
    $this->add('development')
         ->add('staging')
         ->add('production');
  }

  /**
   * Adds a new environment with target key,
   * if we do not have that key already
   * 
   * @param string $key Key for the new environment
   */
  public function add($key)
  {
    if (!is_string($key) || $this->exists($key))
      return $this;

    $this->__envs[$key] = new Env($key);

    return $this;
  }

  /**
   * Replaces an existing environment or adds a new one
   * 
   * @param string $key Key of the environment to replace or add
   */
  public function replace($key)
  {
    if (!is_string($key)) 
      return $this;

    $this->__envs[$key] = new Env($key);

    return $this;
  }

  /**
   * Checks if the target environment exists
   * 
   * @param string $key Key of the environment to check
   */
  public function exists($key)
  {
    if (!is_string($key)) 
      return false;

    return isset($this->__envs[$key]);
  }

  /**
   * Returns the target environment
   * 
   * @param string $key Key of the environment to get
   */
  public function get($key)
  {
    if (!is_string($key)) 
      return null;

    if (!$this->exists($key))
      return null;

    return $this->__envs[$key];
  }

  /**
   * Removes the target environment
   * 
   * @param string $key Key of the environment to remove
   */
  public function remove($key)
  {
    if (!is_string($key)) 
      return $this;

    unset($this->__envs[$key]);

    return $this;
  }

  /**
   * Checks if the target environment is the current one
   * 
   * @param  string  $key Key of the environment to check
   * @return boolean      True if it is the current environment, false otherwise
   */
  public function is($key)
  {
    if (!is_string($key)) 
      return false;

    return $key == $this->getCurrentKey() ? true : false;
  }

  /**
   * Returns the current environment
   * 
   * @return Ponticlaro\Bebop\Env The current environment
   */
  public function getCurrent()
  {
    foreach ($this->__envs as $key => $env) {
        
      if ($env->isCurrent()) 
        return $env;
    }

    // Making sure we have a development environment
    if (!$this->exists('development'))
      $this->add('development');

    return $this->get('development');
  }

  /**
   * Returns the key of the current environment
   * 
   * @return string Key of the current environment
   */
  public function getCurrentKey()
  {
    return $this->getCurrent()->getKey();
  }
}
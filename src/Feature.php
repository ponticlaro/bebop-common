<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;

class Feature {

  /**
   * Featured ID
   * 
   * @var string
   */
  protected $id;

  /**
   * Feature configuration object
   * 
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $config;

  /**
   * Enabled status
   * 
   * @var boolean
   */
  protected $enabled = false;

  /**
   * Instantiates feature
   * 
   * @param string $id     Feature ID
   * @param array  $config Feature configuration array
   */
  public function __construct($id, array $config = array())
  {
    if (!is_string($id))
      throw new \Exception("Feature id must be a string");

    $this->id     = $id;
    $this->config = new Collection();

    if ($config) {
      foreach ($config as $key => $value) {
        $this->set($key, $value);
      }
    }
  }

  /**
   * Enables feature
   * 
   * @return object Current feature object
   */
  public function enable()
  {
    $this->enabled = true;

    return $this;
  }

  /**
   * Disables feature
   * 
   * @return object Current feature object
   */
  public function disable()
  {
    $this->enabled = false;

    return $this;
  }

  /**
   * Checks if feature is enabled
   * 
   * @return object Current feature object
   */
  public function isEnabled()
  {
    return $this->enabled;
  }

  /**
   * Returns featued ID
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
   * @param string $key   Configuration key
   * @param mixed  $value Configuration value
   */
  public function set($key, $value)
  {
    if (is_string($key))
      $this->config->set($key, $value);

    return $this;
  }

  /**
   * Returns a single configuration value
   * 
   * @param  string $key   Configuration key
   * @return mixed  $value Configuration value
   */
  public function get($key)
  {
    return is_string($key) ? $this->config->get($key) : null;
  }

  /**
   * Checks if the target configuration key exists
   * 
   * @param  string $key Configuration key
   * @return boolean     True if exists, false otherwise
   */
  public function has($key)
  {
    return is_string($key) ? $this->config->hasKey($key) : false;
  }

  /**
   * Get the full configuration array
   * 
   * @return array Full configuration array
   */
  public function getAll()
  {
    return $this->config->getAll();
  }
}
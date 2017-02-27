<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Feature;

class FeatureManager {

	/**
	 * Class instance
	 * 
	 * @var object
	 */
	private static $instance;

  /**
   * Features list
   * 
   * @var array
   */
  protected $features = [];

  /**
	 * Instantiates class
	 * 
	 * @return void
	 */
  public function __construct()
  {
    // MVC / Model / Loadables Auto Context
    $loadables_auto_context = (new Feature('mvc/model/loadables_auto_context'))->enable();
    $this->add($loadables_auto_context);

    // HTTP API / V2 Data Models
    $http_api_v2_models = (new Feature('http_api/v2_data_models'))->enable();
    $this->add($http_api_v2_models);
  }

  /**
	 * Do not allow clones
	 * 
	 * @return void
	 */
  private final function __clone() {}

	/**
	 * Gets single instance of called class
	 * 
	 * @return object
	 */
	public static function getInstance() 
	{
		if (!isset(static::$instance))
      static::$instance = new static();

    return static::$instance;
	}

  /**
   * Adds a single feature
   * 
   * @param Feature $feature Feature object to be added
   */
  public function add(Feature $feature)
  {
    $this->features[$feature->getId()] = $feature;

    return $this;
  }

  /**
   * Returns feature object
   * 
   * @param  string $id ID of the target feature
   * @return object     Target feature object
   */
  public function get($id)
  {
    if (!$this->exists($id))
      return null;

    return $this->features[$id];
  } 

  /**
   * Returns all features
   * 
   * @return array List containing all existing features
   */
  public function getAll()
  {
    return $this->features;
  } 

  /**
   * Checks if there is a feature with the target ID
   * 
   * @param  string $id ID of the target feature
   * @return bool       True if exists, false otherwise
   */
  public function exists($id)
  {
    if (!is_string($id))
      return null;

    return isset($this->features[$id]);
  }

  /**
   * Clears features
   * 
   */
  public function clear()
  {
    $this->features = [];

    return $this;
  }
}
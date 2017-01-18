<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\Feature;

class FeatureManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

  /**
   * Features collection
   * 
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $features;

  /**
   * Instantiates feature manager
   * 
   */
  protected function __construct()
  {
    $this->features = new Collection;

    ///////////////////////
    // Built-in features //
    ///////////////////////

    // MVC / Model / Loadables Auto Context
    $loadables_auto_context = (new Feature('mvc/model/loadables_auto_context'))->enable();
    $this->add($loadables_auto_context);

    // HTTP API / V2 Data Models
    $http_api_v2_models = (new Feature('http_api/v2_data_models'))->enable();
    $this->add($http_api_v2_models);
  }

  /**
   * Adds a single feature
   * 
   * @param Feature $feature Feature object to be added
   */
  public function add(Feature $feature)
  {
    $this->features->set($feature->getId(), $feature);

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
    return $this->features->get($id);
  } 

  /**
   * Returns all features
   * 
   * @return array List containing all existing features
   */
  public function getAll()
  {
    return $this->features->getAll();
  } 

  /**
   * Checks if there is a feature with the target ID
   * 
   * @param  string $id ID of the target feature
   * @return bool       True if exists, false otherwise
   */
  public function exists($id)
  {
    return $this->features->hasKey($id);
  }

  /**
   * Clears features
   * 
   */
  public function clear()
  {
    $this->features->clear();

    return $this;
  }
}
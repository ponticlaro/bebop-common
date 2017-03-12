<?php
/**
 * FeatureManager class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Feature;
use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;

/**
 * Collection of features available sitewide
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @api
 */
class FeatureManager {

  use Singleton;

  /**
   * Features list
   * 
   * @var array
   */
  protected $features = [];

  /**
	 * Instantiates class
   *
   * @since 1.0.0
	 */
  public function __construct()
  {
    // MVC / Model / Loadables Auto Context
    $loadables_auto_context = ( new Feature( 'mvc/model/loadables_auto_context' ) )->enable();
    $this->add( $loadables_auto_context );

    // HTTP API / V2 Data Models
    $http_api_v2_models = ( new Feature( 'http_api/v2_data_models' ) )->enable();
    $this->add( $http_api_v2_models );
  }

  /**
   * Adds a single feature
   * 
   * @since 1.0.0
   *
   * @param Feature $feature Feature object to be added
   * @return FeatureManager This class instance
   */
  public function add( Feature $feature )
  {
    $this->features[ $feature->getId() ] = $feature;

    return $this;
  }

  /**
   * Returns feature object
   * 
   * @since 1.0.0
   *
   * @param string $id ID of the target feature
   * @return Feature Target feature object
   */
  public function get( $id )
  {
    if ( ! $this->exists( $id ) )
      return null;

    return $this->features[ $id ];
  } 

  /**
   * Checks if there is a feature with the target ID
   * 
   * @since 1.0.0
   *
   * @param string $id ID of the target feature
   * @return bool True if exists, false otherwise
   */
  public function exists( $id )
  {
    if ( ! is_string( $id ) )
      return false;

    return isset( $this->features[ $id ] );
  }

  /**
   * Returns all features
   * 
   * @since 1.0.0
   *
   * @return array List containing all existing features
   */
  public function getAll()
  {
    return $this->features;
  } 

  /**
   * Clears features
   * 
   * @since 1.0.0
   *
   * @return FeatureManager This class instance
   */
  public function clear()
  {
    $this->features = [];

    return $this;
  }
}
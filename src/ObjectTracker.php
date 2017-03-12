<?php
/**
 * ObjectTracker class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\TrackableObjectInterface;
use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;

/**
 * Object tracker
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @internal
 */
class ObjectTracker {

	use Singleton;

	/**
	 * List with all the tracked object types lists.
	 *
   * @since 1.0.0
   *
	 * @var array
	 */
	protected $lists = [];

  /**
	 * Instantiates class
	 * 
	 * @return void
	 */
  public function __construct() {}

	/**
	 * Tracks trackable objects.
	 * 
   * @since 1.0.0
   *
	 * @param  Ponticlaro\Bebop\Common\Patterns\TrackableObjectInterface $object Object to be tracked
	 * @return ObjectTracker This class instance
	 */
	public function track( TrackableObjectInterface $object )
	{	
		// Get object details
		$id   = (string) $object->getObjectID();
		$type = (string) $object->getObjectType();

		// Add object type collection if not already present
		if ( $type && ! isset( $this->lists[$type] ) ) 
			$this->lists[ $type ] = [];

		// Add object to its type collection
		if( $type && $id ) 
			$this->lists[ $type ][ $id ] = $object;

		return $this;
	}

	/**
	 * Returns a single object by "type" and "id".
	 * 
   * @since 1.0.0
   *
	 * @param string $type Type of the target object
	 * @param string $id ID of the target object
	 * @return object Object that was stored in memory
	 */
	public function get( $type, $id )
	{
		if ( ! is_string( $type ) || ! is_string( $id ) )
			throw new \Exception( "\$type and \$id arguments must be both strings" );

		if ( ! isset( $this->lists[ $type ] ) )
			return null;

		if ( ! isset( $this->lists[ $type ][ $id ] ) )
			return null;

		return $this->lists[ $type ][ $id ];
	}
}
<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\TrackableObjectInterface;

class ObjectTracker {

	/**
	 * Class instance
	 * 
	 * @var object
	 */
	private static $instance;

	/**
	 * List with all the tracked object types lists
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
	 * Tracks Bebop objects
	 * 
	 * @param  mixed $object Object to be tracked
	 * @return void
	 */
	public function track(TrackableObjectInterface $object)
	{	
		// Get object details
		$id   = (string) $object->getObjectID();
		$type = (string) $object->getObjectType();

		// Add object type collection if not already present
		if ($type && !isset($this->lists[$type])) 
			$this->lists[$type] = [];

		// Add object to its type collection
		if($type && $id) 
			$this->lists[$type][$id] = $object;

		return $this;
	}

	/**
	 * Returns a single object by "type" and "id"
	 * 
	 * @param  string $type Type of the target object
	 * @param  string $id   ID of the target object
	 * @return object       Object that was stored in memory
	 */
	public function get($type, $id)
	{
		if (!is_string($type) || !is_string($id))
			throw new \Exception("\$type and \$id arguments must be both strings");

		if (!isset($this->lists[$type]))
			return null;

		if (!isset($this->lists[$type][$id]))
			return null;

		return $this->lists[$type][$id];
	}
}
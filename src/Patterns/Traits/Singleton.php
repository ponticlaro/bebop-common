<?php

namespace Ponticlaro\Bebop\Common\Patterns\Traits;

trait Singleton {

	/**
	 * Class instance
	 * 
	 * @var object
	 */
	private static $instance;

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
}
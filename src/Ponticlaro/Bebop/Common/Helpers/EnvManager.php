<?php

namespace Ponticlaro\Bebop\Common\Helpers;

use Ponticlaro\Bebop\Common\Collection;

class EnvManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

	/**
	 * List of environments
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection;
	 */
	protected $__environments;

	/**
	 * Instantiates Env Manager object
	 * 
	 */
	protected function __construct()
	{
		// Instantiate environments collection object
		$this->__environments = (new Collection(array(
			'development' => new Env('development'),
			'staging'     => new Env('staging'),
			'production'  => new Env('production')
		)))->disableDottedNotation();
	}

	/**
	 * Adds a new environment with target key,
	 * if we do not have that key already
	 * 
	 * @param string $key Key for the new environment
	 */
	public function add($key)
	{
		if (!is_string($key) || $this->__environments->hasKey($key)) return $this;

		$this->__environments->set($key, new Env($key));

		return $this;
	}

	/**
	 * Replaces an existing environment or adds a new one
	 * 
	 * @param string $key Key of the environment to replace or add
	 */
	public function replace($key)
	{
		if (!is_string($key)) return $this;

		$this->__environments->set($key, new Env($key));

		return $this;
	}

	/**
	 * Checks if the target environment exists
	 * 
	 * @param string $key Key of the environment to check
	 */
	public function exists($key)
	{
		if (!is_string($key)) return false;

		return $this->__environments->hasKey($key);
	}

	/**
	 * Returns the target environment
	 * 
	 * @param string $key Key of the environment to get
	 */
	public function get($key)
	{
		if (!is_string($key)) return $this;

		return $this->__environments->get($key);
	}

	/**
	 * Removes the target environment
	 * 
	 * @param string $key Key of the environment to remove
	 */
	public function remove($key)
	{
		if (!is_string($key)) return $this;

		$this->__environments->remove($key);

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
		if (!is_string($key) || !$this->__environments->hasKey($key)) return false;

		$env = $this->__environments->get($key);

		return $env->hasHost($_SERVER['SERVER_NAME']);
	}

	/**
	 * Returns the current environment
	 * 
	 * @return Ponticlaro\Bebop\Helpers\Env The current environment
	 */
	public function getCurrent()
	{
		$envs = $this->__environments->getAll();

		foreach ($envs as $key => $env) {
			
			if ($env->isCurrent()) return $env;
		}

		return $this->__environments->get('development');
	}

	/**
	 * Returns the key of the current environment
	 * 
	 * @return string Key of the current environment
	 */
	public function getCurrentKey()
	{
		$current_env = $this->getCurrent();

		return $current_env instanceof \Ponticlaro\Bebop\Common\Helpers\Env ? $current_env->getKey() : 'development';
	}
}
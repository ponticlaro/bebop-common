<?php

namespace Ponticlaro\Bebop\Common\Helpers;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\Helpers\ContextContainer;

class ContextManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

	/**
	 * Contains Context Manager instance
	 * 
	 * @var Ponticlaro\Bebop\Common\Helpers\ContextManager
	 */
	private $instance;

	/**
	 * Current context key
	 * 
	 * @var string
	 */
	protected $current;

	/**
	 * Used to store the current context when
	 * manually defining the context key
	 * 
	 * @var string
	 */
	protected $current_backup;

	/**
	 * List of Context Containers
	 *  
	 * @var Ponticlaro\Bebop\Common\Collection
	 */
	protected $contexts;

	/**
	 * Instantiates Context Manager
	 * 
	 */
	protected function __construct()
	{
		// Instantiate contexts collection
		$this->contexts = (new Collection())->disableDottedNotation();

		// Add default context rules
		$this->add('default', function($query) {

		    $key = null;

			if (is_home()) { $key = 'home/posts'; }

		    elseif (is_front_page()) { $key = 'home/page'; }

		    elseif (is_search()) { $key = 'search'; }

		    elseif (is_404()) { $key = 'error/404'; }

		    elseif (is_category()) { $key = 'tax/category'; }

		    elseif (is_tag()) { $key = 'tax/tag'; }

		    elseif (is_tax()) { $key = 'tax/'. $query->query_vars['taxonomy']; }

		    elseif (is_post_type_archive()) { $key = 'archive/'. $query->query_vars['post_type']; }
		    
		    elseif (is_date()) {
		        
		        if (is_year()) { $key = 'archive/date/year'; }

		        elseif (is_month()) { $key = 'archive/date/month'; }

		        elseif (is_day()) { $key = 'archive/date/day'; }
		    }

		    elseif (is_author()) { $key = 'archive/author'; }

		    elseif (is_singular()) {
		        
		        if (is_singular('post')) { $post_type = 'post'; } 

		        elseif (is_singular('page')) { $post_type = 'page'; }

		        else { $post_type = $query->query_vars['post_type']; }

		        $key = 'single/'. $post_type;
		    }

		    return $key;
		});
		
		// Add action to define current context
		add_action('wp', array($this, 'defineCurrent'));
	}

	/**
	 * Defines current contexts by running all
	 * context containers until it finds a match
	 * 
	 * @return void
	 */
	public function defineCurrent()
	{
		foreach ($this->contexts->getAll() as $context_container) {
			
			$key = $context_container->run();

			if ($key) {

				$this->current = $key;
				break;
			}
		}
	}

	/**
	 * Checks if the target $key is a partial match for the current context
	 * You can optionally pass a regular expression to find matches
	 * 
	 * @param  string  $key        Context key or regular expression
	 * @param  boolean $is_pattern True if the $key should be treated as a regular expression
	 * @return boolean             True if there is a match, false otherwise
	 */
	public function is($key, $is_pattern = false)
	{
		$pattern = $is_pattern ? $key : '/^'. preg_quote($key, '/') .'*/';

		return preg_match($pattern, $this->current) ? true : false;	
	}

	/**
	 * Checks if the target $key is an exact match for the current context
	 * 
	 * @param  string  $key Context string to check
	 * @return boolean      True if it partially or fully matches, false otherwhise
	 */
	public function equals($key)
	{
		return $this->current === $key ? true : false;
	}

	/**
	 * Returns current context key
	 * 
	 * @return string
	 */
	public function getCurrent()
	{
		return $this->current;
	}

	/**
	 * Overrides current context with target $key,
	 * but also keeps a backup of the current key to later be restored
	 * 
	 * @param  string $key
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextManager Context Manager instance
	 */
	public function overrideCurrent($key)
	{
		if (is_string($key)) {
			
			$this->current_backup = $this->current;
			$this->current        = $key;
		}

		return $this->instance;
	}

	/**
	 * Restores previois context key, if there is any
	 * 
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextManager Context Manager instance
	 */
	public function restoreCurrent()
	{
		if (!is_null($this->current_backup)) {
			
			$this->current        = $this->current_backup;
			$this->current_backup = null;
		}

		return $this->instance;
	}

	/**
	 * Returns single context container by ID
	 * 
	 * @param  string                                           $id ID of the target context container
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextContainer     Target context container
	 */
	public function get($id)
	{
		return $this->__getContextById($id);
	}

	/**
	 * Adds a single context container to the top of the list
	 * 
	 * @param string $id Context Container ID
	 * @param string $fn Context Container function
	 */
	public function add($id, $fn)
	{
		$this->prepend($id, $fn);
	}

	/**
	 * Adds a single context container to the top of the list
	 * 
	 * @param  string                                         $id Context Container ID
	 * @param  string                                         $fn Context Container function
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextManager     Context Manager instance
	 */
	public function prepend($id, $fn)
	{
		if (is_string($id) && is_callable($fn)) {

			$this->contexts->unshift(new ContextContainer($id, $fn));
		}

		return $this->instance;
	}

	/**
	 * Adds a single context container to the bottom of the list
	 * 
	 * @param  string                                         $id Context Container ID
	 * @param  string                                         $fn Context Container function
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextManager     Context Manager instance
	 */
	public function append($id, $fn)
	{	
		if (is_string($id) && is_callable($fn)) {

			$this->contexts->push(new ContextContainer($id, $fn));
		}

		return $this->instance;
	}

	/**
	 * Returns a single context container by ID
	 * 
	 * @param  string                                           $id ID of the target context container
	 * @return Ponticlaro\Bebop\Common\Helpers\ContextContainer     Target context container
	 */
	private function __getContextById($id)
	{
		$target_context = null;

		foreach ($this->contexts->getAll() as $context) {
			
			if ($context->getId() == $id) {
				
				$target_context = $context;
				break;
			}
		}

		return $target_context;
	}
}
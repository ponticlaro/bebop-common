<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\ContextContainer;

/**
 * This class must be instantiated via getInstance() before the 'wp' action hook,
 * otherwise it won't be able to get the current context
 *
 * Consequently all context containers must also be added before the 'wp' action hook,
 * otherwise those won't be used to find the correct context
 * 
 */
class ContextManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

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
   * @var array
   */
  protected $current_backups = [];

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
    $this->add('default', function($q) {

      $key = null;

      if ($q->is_home()) { $key = 'home/posts'; }

      elseif ($q->is_front_page()) { $key = 'home/page'; }

      elseif ($q->is_search()) { $key = 'search'; }

      elseif ($q->is_404()) { $key = 'error/404'; }

      elseif ($q->is_category()) { $key = 'tax/category'; }

      elseif ($q->is_tag()) { $key = 'tax/tag'; }

      elseif ($q->is_tax()) { $key = 'tax/'. $q->get('taxonomy'); }

      elseif ($q->is_post_type_archive()) { $key = 'archive/'. $q->get('post_type'); }
      
      elseif ($q->is_date()) {
          
        if ($q->is_year()) { $key = 'archive/date/year'; }

        elseif ($q->is_month()) { $key = 'archive/date/month'; }

        elseif ($q->is_day()) { $key = 'archive/date/day'; }
      }

      elseif ($q->is_author()) { $key = 'archive/author'; }

      elseif ($q->is_singular()) { 

        if ($q->is_page()) { $post_type = 'page'; }

        elseif($q->get('post_type')) { $post_type = $q->get('post_type'); }

        else { $post_type = 'post'; }

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

    return $this;
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
   * @return Ponticlaro\Bebop\Common\ContextManager Context Manager instance
   */
  public function overrideCurrent($key)
  {
    if (is_string($key)) {
        
      $this->current_backups[] = $this->current;
      $this->current           = $key;
    }

    return $this;
  }

  /**
   * Restores previois context key, if there is any
   * 
   * @return Ponticlaro\Bebop\Common\ContextManager Context Manager instance
   */
  public function restoreCurrent()
  {
    if ($this->current_backups)
      $this->current = array_pop($this->current_backups);

    return $this;
  }

  /**
   * Returns single context container by ID
   * 
   * @param  string                                   $id ID of the target context container
   * @return Ponticlaro\Bebop\Common\ContextContainer     Target context container
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

    return $this;
  }

  /**
   * Adds a single context container to the top of the list
   * 
   * @param  string                                 $id Context Container ID
   * @param  callable                               $fn Context Container function
   * @return Ponticlaro\Bebop\Common\ContextManager     Context Manager instance
   */
  public function prepend($id, callable $fn)
  {
    if (is_string($id))
      $this->contexts->unshift(new ContextContainer($id, $fn));

    return $this;
  }

  /**
   * Adds a single context container to the bottom of the list
   * 
   * @param  string                                 $id Context Container ID
   * @param  callable                               $fn Context Container function
   * @return Ponticlaro\Bebop\Common\ContextManager     Context Manager instance
   */
  public function append($id, callable $fn)
  {   
    if (is_string($id))
      $this->contexts->push(new ContextContainer($id, $fn));

    return $this;
  }

  /**
   * Returns a single context container by ID
   * 
   * @param  string                                   $id ID of the target context container
   * @return Ponticlaro\Bebop\Common\ContextContainer     Target context container
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
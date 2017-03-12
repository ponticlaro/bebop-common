<?php
/**
 * Context Manager class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\SingletonTrait;

/**
 * Determines the current context from the global \WP_Query instance.
 *
 * This class must be instantiated via getInstance() before the 'wp' action hook,
 * otherwise it won't be able to get the current context.
 *
 * Consequently all Context Containers must also be added before the 'wp' action hook,
 * otherwise those won't be used to find the correct context.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @since 1.1.5 Uses SingletonTrait trait
 * @api
 */
class ContextManager {

  use SingletonTrait;

  /**
   * Current context key.
   * 
   * @since 1.0.0
   *
   * @var string
   */
  protected $current;

  /**
   * Used to store the current context when manually defining the context key.
   * 
   * @since 1.0.0
   *
   * @var array
   */
  protected $current_backups = [];

  /**
   * List of Context Containers.
   *  
   * @since 1.0.0
   *
   * @var array
   */
  protected $contexts = [];

  /**
   * Instantiates Context Manager.
   * 
   * @since 1.0.0
   */
  public function __construct()
  {
    // Add default context rules
    $this->add( 'default', function( $q ) {

      $key = null;

      if ( $q->is_home() ) { $key = 'home/posts'; }

      elseif ( $q->is_front_page() ) { $key = 'home/page'; }

      elseif ( $q->is_search() ) { $key = 'search'; }

      elseif ( $q->is_404() ) { $key = 'error/404'; }

      elseif ( $q->is_category() ) { $key = 'tax/category'; }

      elseif ( $q->is_tag() ) { $key = 'tax/tag'; }

      elseif ( $q->is_tax() ) { $key = 'tax/'. $q->get('taxonomy'); }

      elseif ( $q->is_post_type_archive() ) { $key = 'archive/'. $q->get('post_type'); }
      
      elseif ( $q->is_date() ) {
          
        if ( $q->is_year() ) { $key = 'archive/date/year'; }

        elseif ( $q->is_month() ) { $key = 'archive/date/month'; }

        elseif ( $q->is_day() ) { $key = 'archive/date/day'; }
      }

      elseif ( $q->is_author() ) { $key = 'archive/author'; }

      elseif ( $q->is_singular() ) { 

        if ( $q->is_page() ) { $post_type = 'page'; }

        elseif( $q->get('post_type') ) { $post_type = $q->get('post_type'); }

        else { $post_type = 'post'; }

        $key = 'single/'. $post_type;
      }

      return $key;
    });
    
    // Add action to define current context
    add_action( 'wp', [ $this, 'defineCurrent' ]);
  }

  /**
   * Defines current contexts by running all context containers until it finds a match.
   * 
   * @since 1.0.0
   * 
   * @return ContextManager Context Manager instance
   */
  public function defineCurrent()
  {
    global $wp_query;

    foreach ( $this->contexts as $context_container ) {
        
      $key = $context_container->run( $wp_query );

      if ( $key ) {

        $this->current = $key;
        break;
      }
    }

    return $this;
  }

  /**
   * Checks if the target $key is a partial match for the current context.
   * 
   * You can optionally pass a regular expression to find matches.
   * 
   * @since 1.0.0
   *
   * @param string $key Context key or regular expression
   * @param boolean $is_pattern True if the $key should be treated as a regular expression
   * @return boolean True if there is a match, false otherwise
   */
  public function is( $key, $is_pattern = false )
  {
    $pattern = $is_pattern ? $key : '/^'. preg_quote( $key, '/' ) .'*/';

    return preg_match( $pattern, $this->current ) ? true : false; 
  }

  /**
   * Checks if the target $key is an exact match for the current context.
   * 
   * @since 1.0.0
   *
   * @param string $key Context string to check
   * @return boolean True if it partially or fully matches, false otherwhise
   */
  public function equals( $key )
  {
    return $this->current === $key ? true : false;
  }

  /**
   * Returns current context key.
   * 
   * @since 1.0.0
   *
   * @return string
   */
  public function getCurrent()
  {
    return $this->current;
  }

  /**
   * Overrides current context.
   *
   * Keeps a backup of the current key to later be restored.
   * Multiple overrides are allowed and tracked.
   * 
   * @since 1.0.0
   *
   * @param  string $key
   * @return ContextManager Context Manager instance
   */
  public function overrideCurrent($key)
  {
    if ( is_string( $key ) ) {
      $this->current_backups[] = $this->current;
      $this->current           = $key;
    }

    return $this;
  }

  /**
   * Restores previous context key, if there is any.
   * 
   * Multiple restores are allowed and tracked.
   * 
   * @since 1.0.0
   *
   * @return ContextManager Context Manager instance
   */
  public function restoreCurrent()
  {
    if ( $this->current_backups )
      $this->current = array_pop( $this->current_backups );

    return $this;
  }

  /**
   * Returns single context container by ID.
   * 
   * @since 1.0.0
   * @deprecated 1.1.5
   *
   * @param string $id ID of the target context container
   * @return ContextContainer Target context container
   */
  public function get( $id )
  {
    return $this->__getContextById( $id );
  }

  /**
   * Adds a single context container to the top of the list.
   * 
   * @since 1.0.0
   *
   * @param string $id Context Container ID
   * @param callable $fn Context Container function
   */
  public function add( $id, callable $fn )
  {
    $this->prepend( $id, $fn );

    return $this;
  }

  /**
   * Adds a single context container to the top of the list.
   * 
   * @since 1.0.0
   * @uses Ponticlaro\Bebop\Common\ContextContainer
   *
   * @param string $id Context Container ID
   * @param callable $fn Context Container function
   * @return ContextManager Context Manager instance
   */
  public function prepend( $id, callable $fn )
  {
    if ( is_string( $id ) )
      array_unshift( $this->contexts, new ContextContainer( $id, $fn ) );

    return $this;
  }

  /**
   * Adds a single context container to the bottom of the list.
   * 
   * @since 1.0.0
   * @uses ContextContainer
   *
   * @param string $id Context Container ID
   * @param callable $fn Context Container function
   * @return ContextManager Context Manager instance
   */
  public function append( $id, callable $fn )
  {   
    if ( is_string( $id ) )
      $this->contexts[] = new ContextContainer( $id, $fn );

    return $this;
  }

  /**
   * Returns a single context container by ID.
   * 
   * @since 1.0.0
   *
   * @param string $id ID of the target context container
   * @return ContextContainer Target context container
   */
  private function __getContextById( $id )
  {
    $target_context = null;

    foreach ( $this->contexts as $context ) {
        
      if ( $context->getId() == $id ) {
          
        $target_context = $context;
        break;
      }
    }

    return $target_context;
  }
}
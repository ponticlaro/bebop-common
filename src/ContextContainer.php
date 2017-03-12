<?php
/**
 * Context Container class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

/**
 * Callable wrapper used to determine the current context from a \WP_Query instance.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @internal
 */
class ContextContainer {

  /**
   * Context Container ID
   *
   * @since 1.0.0
   *
   * @var string
   */
  private $id;

  /**
   * Context Container function
   *
   * @since 1.0.0
   *
   * @var callable
   */
  private $function;

  /**
   * Instantiates a Context Container
   *
   * @since 1.0.0
   *
   * @param string $id ID
   * @param string $function Function to execute
   */
  public function __construct( $id, callable $function )
  {
    if ( ! is_string( $id ) )
      throw new \Exception('$id must be a string');
    
    $this->id       = $id;
    $this->function = $function;
  }

  /**
   * Returns ID
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Returns function
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function getFunction()
  {
    return $this->function;
  }

  /**
   * Executes Context Container function,
   * passing $wp_query as the first argument
   *
   * @since 1.0.0
   *
   * @return string The current context key or null, if none was found
   */
  public function run( \WP_Query $wp_query )
  {
    return call_user_func_array( $this->function, [ $wp_query ] );
  }
}
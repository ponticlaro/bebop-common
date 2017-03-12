<?php
/**
 * FactoryAbstract class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Abstract class to create factories.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @internal
 */
abstract class FactoryAbstract {

  /**
   * List of manufacturable classes
   * 
   * @since 1.0.0
   * 
   * @var array
   */
  protected static $manufacturable = [];

  /**
   * Making sure class cannot get instantiated
   * 
   * @since 1.0.0
   */
  protected function __construct() {}

  /**
   * Making sure class cannot get instantiated
   *
   * @since 1.0.0
   */
  protected function __clone() {}

  /**
   * Adds a new manufacturable class
   * 
   * @since 1.0.0
   * 
   * @param string $type Object type ID
   * @param string $class Full namespace for a class
   * @return void
   */
  public static function set( $type, $class )
  {
    static::$manufacturable[ $type ] = $class;
  }

  /**
   * Returns the class for the target manufacturable
   * 
   * @since 1.1.4
   * 
   * @param string $type Object type ID
   * @return string Object type class
   */
  public static function get( $type )
  {
    return is_string( $type ) && isset( static::$manufacturable[ $type ] ) ? static::$manufacturable[ $type ] : null;
  }

  /**
   * Removes a new manufacturable class
   * 
   * @since 1.0.0
   * 
   * @param string $type Object type ID
   * @return void
   */
  public static function remove( $type )
  {
    if ( isset( static::$manufacturable[ $type ] ) ) 
      unset( static::$manufacturable[ $type ] );
  }

  /**
   * Checks if there is a manufacturable with target key
   * 
   * @since 1.0.0
   * 
   * @param string $key Target key
   * @return boolean True if key exists, false otherwise
   */
  public static function canManufacture( $key )
  {
    return is_string( $key ) && isset( static::$manufacturable[ $key ] ) ? true : false;
  }

  /**
   * Returns the id to manufacture another instance of the passed object, if any
   * 
   * @since 1.0.0
   * 
   * @param object $instance Arg instance
   * @return string Arg ID 
   */
  public static function getInstanceId( $instance )
  { 
    if ( is_object( $instance ) ) {

      $class = get_class( $instance );
      $id    = array_search( $class, static::$manufacturable );

      return $id ?: null;
    }

    return null;
  }

  /**
   * Creates instance of target class
   * 
   * @since 1.0.0
   * 
   * @param string $type Class ID
   * @param array $args Class arguments
   * @return object Class instance
   */
  public static function create( $type, array $args = [] )
  {
    // Check if target is in the allowed list
    if ( array_key_exists( $type, static::$manufacturable ) ) {

      $class_name = static::$manufacturable[ $type ];

      // Get an instance of the target class
      $obj = call_user_func_array( [ new \ReflectionClass( $class_name ), 'newInstance' ], $args );

      // Return object
      return $obj;
    }

    // Return null if target object is not manufacturable
    return null;
  }
}
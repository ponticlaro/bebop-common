<?php
/**
 * CollectionRelativePathMutator trait.
 *
 * @package Bebop\Common
 * @since 1.1.5
 */

namespace Ponticlaro\Bebop\Common\Patterns\Traits;

/**
 * Extension for the Collection class that facilitates path handling
 *
 * @package Bebop\Common
 * @since 1.1.5
 * @api
 * @see \Ponticlaro\Bebop\Common\Patterns\Collection
 */
trait CollectionPathMutator {

  /**
   * Used to store a single path using a key
   * 
   * @since 1.1.5
   *
   * @param string $path Key 
   * @param string $value Path
   * @return object This class instance
   */
  public function set( $path, $value = true )
  {
    parent::set( $path, $value == '/' ? $value : rtrim( $value, '/' ) );

    return $this;
  }

  /**
   * Checks if the target path exists
   * 
   * @since 1.1.5
   *
   * @param string $key Key of the path to check
   */
  public function has( $key )
  {
    return $this->hasKey( $key );
  }

  /**
   * Returns a single path; optional suffixed relative path
   * 
   * @since 1.1.5
   *
   * @param string $key Key for the target path
   * @param string $relative_path Optional relative path
   * @return string Path
   */
  public function get( $key, $relative_path = null )
  {   
    if ( ! is_string( $key ) )
      return null;

    if ( ! $this->hasKey( $key ) )
      return null;

    // Get path without trailing slash
    $path = parent::get( $key );

    // Concatenate relative path
    if ( $relative_path )
      $path = rtrim( $path, '/' ) .'/'. ltrim( $relative_path, '/' );

    return $path; 
  }
}
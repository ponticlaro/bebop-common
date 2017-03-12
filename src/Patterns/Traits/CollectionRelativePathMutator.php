<?php

namespace Ponticlaro\Bebop\Common\Patterns\Traits;

trait CollectionRelativePathMutator {

  /**
   * Used to store a single URL using a key
   * 
   * @param string $path Key 
   * @param string $value URL
   * @return CollectionInterface This class instance
   */
  public function set( $path, $value = true )
  {
    parent::set( $path, $value == '/' ? $value : rtrim( $value, '/' ) );

    return $this;
  }

  /**
   * Checks if the target path exists
   * 
   * @param string $key Key of the path to check
   */
  public function has( $key )
  {
    return $this->hasKey( $key );
  }

  /**
   * Returns a single URL using a key
   * with an optionally suffixed realtive URL
   * 
   * @param  string $key          Key for the target URL
   * @param  string $relative_url Optional relative URL
   * @return string               URL
   */
  public function get( $key, $relative_url = null )
  {   
    if ( ! is_string( $key ) )
      return null;

    if ( ! $this->hasKey( $key ) )
      return null;

    // Get url without trailing slash
    $url = parent::get( $key );

    // Concatenate relative URL
    if ( $relative_url )
      $url = rtrim( $url, '/' ) .'/'. ltrim( $relative_url, '/' );

    return $url; 
  }
}
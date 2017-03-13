<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\CollectionInterface;

/**
 * Parameter collection to easily handle multidimensional configuration arrays.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @api
 * @see \Ponticlaro\Bebop\Common\Patterns\CollectionInterface Implemented collection interface
 */
class Collection implements CollectionInterface, \IteratorAggregate, \Countable {

  /**
   * Array that contains all the data.
   * 
   * @since 1.0.0
   * 
   * @var array
   */
  protected $data = [];

  /**
   * Status of multidimensional arrays access through dotted notation.
   * 
   * @since 1.0.0
   * 
   * @var boolean
   */
  protected $dotted_notation_enabled = true;

  /**
   * Separator for path keys.
   * 
   * @since 1.0.0
   * 
   * @var string
   */
  protected $path_separator = '.';
  
  /**
   * {@inheritDoc}
   */
  public function __construct( array $data = [] )
  {
    $this->set( $data );
  }

  /**
   * {@inheritDoc}
   */
  public function enableDottedNotation()
  {
    $this->dotted_notation_enabled = true;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function disableDottedNotation()
  {
    $this->dotted_notation_enabled = false;

    return $this;
  }

  /**
   * Checks if dotted notation is enabled
   * 
   * @since 1.0.0
   * 
   * @return boolean True if enabled, false otherwise
   */
  protected function isDottedNotationEnabled()
  {
    return $this->dotted_notation_enabled;
  }

  /**
   * {@inheritDoc}
   */
  public function setPathSeparator( $separator )
  {
    if ( is_string( $separator ) )
      $this->path_separator = $separator;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function clear()
  {   
    $this->data = [];

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function set( $path, $value = true )
  {
    if ( is_string( $path ) || is_numeric( $path ) ) {

      $this->__set( $path, $value );
    }

    elseif ( is_array( $path ) ) {

      $this->setList( $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function setList( array $values )
  {
    foreach ( $values as $path => $value ) {
      $this->__set ($path, $value );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function add( $path, $values )
  {
    $data = $this->__get( $path );

    if ( is_array( $values ) ) {

      if ( is_array( $data ) ) {
          
        $data = array_merge( $data, $values );
      }

      else {

        $data = $values;
      }
    } 

    else {

      $data[] = $values;
    }

    $this->__set( $path, $data );

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function shift( $path = null )
  {
    if ( $path ) {
        
      $data = $this->__get( $path );

      if ( is_array( $data ) ) {
          
        $value = array_shift( $data );

        $this->__set( $path, $data );
      }
    }

    else {

      $value = isset( $this->data[0] ) ? array_shift( $this->data ) : null;
    }

    return $value;
  }

  /**
   * {@inheritDoc}
   */
  public function unshift( $value, $path = null )
  {
    if ( is_array( $value ) ) {

      $this->unshiftList( $value, $path );
    }

    else {
        
      $this->__unshiftItem( $value, $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function unshiftList( array $values, $path = null )
  {
    foreach ( array_reverse( $values ) as $value ) {
      $this->unshift( $value, $path );
    }

    return $this;
  }

  /**
   * Adds an item to to beginning of the target array.
   *
   * @since 1.0.0
   * 
   * @param mixed $value The valued to be inserted
   * @param string $path Optional path to unshift the value to
   * @return void      
   */
  private function __unshiftItem( $value, $path = null )
  {
    if ( $path ) {
        
      $data = $this->__get( $path );

      if ( is_array( $data ) ) {

        array_unshift( $data, $value );
      } 

      else {

        $data = [ $value ];
      }

      $this->__set( $path, $data );
    }

    else {

      array_unshift( $this->data, $value );
    }
  }

  /**
   * {@inheritDoc}
   */
  public function push( $value, $path = null )
  {
    if ( $path ) {
        
      $data = $this->__get( $path );

      if ( is_array( $data ) ) {

        $data[] = $value;    
      } 

      else {

        $data = [ $value ];
      }

      $this->__set( $path, $data );
    }

    else {

      $this->data[] = $value;
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function pushList( array $values, $path = null )
  {
    foreach ( $values as $value ) {  
      $this->push( $value, $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function pop( $value, $path = null )
  {
    if ( $path ) {
        
      $data = $this->__get( $path );

      if ( is_array( $data ) ) {

        $key = array_search( $value, $data );

        if ( $key !== false ) 
          $this->__unset( $path . $this->path_separator . $key );
      } 

      else {

        $this->__unset( $path );
      }
    }

    else {

      $key = array_search( $value, $this->data );

      if ( $key !== false ) 
        unset( $this->data[ $key ] );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function popList( array $values, $path = null )
  {
    foreach ( $values as $value ) {
      $this->pop( $value, $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function remove( $path )
  {
    if ( is_string( $path ) ) {
        
      $this->__unset( $path );
    }

    elseif ( is_array( $path ) ) {

      $this->removeList( $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function removeList( array $paths )
  {
    foreach ( $paths as $path ) {
      $this->remove( $path );
    }

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function get( $path )
  {
    if ( is_string( $path ) ) {

      return $this->__get( $path );
    }

    elseif ( is_array( $path ) ) {

      return $this->getList( $path );
    }

    return null;
  }

  /**
   * {@inheritDoc}
   */
  public function getList( array $paths )
  {
    $results = [];

    foreach ( $paths as $path ) {

      if ( is_string( $path ) ) {

        if ( $this->isDottedNotationEnabled() ) {

          $data  = &$results;
          $paths = explode( $this->path_separator, $path );

          while ( count( $paths ) > 1 ) {
                  
            $key = array_shift( $paths );

            if ( ! isset( $data[ $key ] ) || ! is_array( $data[ $key ] ) ) {

              $data[ $key ] = []; 
            }
            
            $data = &$data[ $key ];
          }

          $data[ array_shift( $paths ) ] = $this->__get( $path );
        }

        else {

          $results[ $path ] = $this->__get( $path );
        }
      }
    }

    return $results;
  }

  /**
   * {@inheritDoc}
   */
  public function getAll()
  {
    return $this->data;
  }

  /**
   * {@inheritDoc}
   */
  public function getKeys( $path = null )
  {   
    $data = $path ? $this->__get( $path ) : $this->data;

    return is_array( $data ) ? array_keys( $data ) : null;
  }

  /**
   * {@inheritDoc}
   */
  public function hasKey( $path )
  {
    return $this->__hasPath( $path ) ? true : false;
  }

  /**
   * {@inheritDoc}
   */
  public function hasValue( $value, $path = null )
  {
    if ( $path ) {
        
      $data = $this->__get( $path );

      if ( is_array( $data ) ) {

        $key = array_search( $value, $data );
      } 

      else {

        $key = $data == $value ? true : false;
      }
    }

    else {

      $key = array_search( $value, $this->data );
    }

    return $key === false ? false : true;
  }

  /**
   * {@inheritDoc}
   */
  public function count( $path = null )
  {
    $data = $path ? $this->__get( $path ) : $this->data;

    return $data && is_array( $data ) ? count( $data ) : null;
  }

  /**
   * {@inheritDoc}
   */
  public function getIterator()
  {
    return new \ArrayIterator( $this->data );
  }

  /**
   * Taking control over the __set overloading magic method.
   * 
   * @since 1.0.0
   * @internal
   * 
   * @param string $path Path that will hold the $value
   * @param mixed $value Value to be stored
   * @return Collection This class instance
   */
  public function __set( $path, $value )
  {   
    if ( is_string( $path ) ) {

      if ( $this->isDottedNotationEnabled() ) {

        // Get current data as reference
        $data = &$this->data;
            
        // Explode keys
        $keys = explode( $this->path_separator, $path );

        // Crawl though the keys
        while ( count( $keys ) > 1 ) {

          $key = array_shift( $keys );

          if ( ! isset( $data[ $key ] ) ) {

            $data[ $key ] = [];
          }
          
          $data =& $data[ $key ];
        }

        if ( is_array( $data ) ) {
            
          $data[ array_shift( $keys ) ] = $value;
        }

        else {

          $data = $value;
        }
      }

      else {

          $this->data[ $path ] = $value;
      }
    }

    elseif ( is_numeric( $path ) ) {
        
      $this->push( $value );
    }

    return $this;
  }

  /**
   * Taking control over the __get overloading magic method.
   * 
   * @since 1.0.0
   * @internal
   * 
   * @param string $path Path to look for and return its value
   * @return mixed Value of the key or null
   */
  public function __get( $path )
  {
    if ( ! is_string( $path ) || ! $this->__hasPath( $path ) ) 
      return null;

    if ( $this->isDottedNotationEnabled() ) {

      // Get current data as reference
      $data = &$this->data;

      // Explode keys
      $keys = explode( $this->path_separator, $path );

      // Crawl though the keys
      while ( count( $keys ) > 1 ) {

        $key = array_shift( $keys );

        if ( ! isset( $data[ $key ] ) ) {

          return null;
        }
        
        $data =& $data[ $key ];
      }

      return $data[ array_shift( $keys ) ];
    }

    return $this->data[ $path ];
  }

  /**
   * Taking control over the __unset overloading magic method.
   * 
   * @since 1.0.0
   * @internal
   * 
   * @param string $path Path to be unset
   * @return Collection This class instance
   */
  public function __unset( $path )
  {
    if ( is_string( $path ) ) {

      if ( $this->isDottedNotationEnabled() ) {

        // Get current data as reference
        $data = &$this->data;

        // Explode keys
        $keys = explode( $this->path_separator, $path );

        // Crawl though the keys
        while ( count( $keys ) > 1 ) {

          $key = array_shift( $keys );

          if ( ! isset( $data[ $key ] ) ) {

            return $this;
          }
          
          $data =& $data[ $key ];
        }

        unset( $data[ array_shift( $keys ) ] );
      }

      else {

        if ( isset( $this->data[ $path ] ) )
          unset( $this->data[ $path ] );
      }
    }

    return $this;
  }

  /**
   * Checks if the target path exists.
   * 
   * @since 1.0.0
   * @internal
   * 
   * @param string $path Target path to ve checked
   * @return boolean True if exists, false otherwise
   */
  protected function __hasPath( $path )
  {   
    if ( ! is_string( $path ) ) 
      return false;

    if ( $this->isDottedNotationEnabled() ) {

      // Get current data as reference
      $data = &$this->data;

      // Explode keys
      $keys = explode( $this->path_separator, $path );

      // Crawl though the keys
      while ( count( $keys ) > 1 ) {

        $key = array_shift( $keys );

        if ( ! isset( $data[ $key ] ) ) {

          return false;
        }
        
        $data =& $data[ $key ];
      }

      return isset( $data[ array_shift( $keys ) ] ) ? true : false;
    }

    else {

      return isset( $this->data[ $path ] ) ? true : false;
    }
  }
}
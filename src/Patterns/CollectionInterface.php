<?php
/**
 * Collection interface.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Parameter collection interface to easily handle multidimensional configuration arrays.
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @api
 */
interface CollectionInterface { 

  /**
   * Initialize Collection with optionally passed array containing initial data.
   * 
   * @since 1.0.0
   * 
   * @param array $data Optional initial data to be added
   */
  public function __construct(array $data = []);

  /**
   * Enables dotted notation to access multidimensional arrays
   * 
   * @since 1.0.0
   * 
   * @return CollectionInterface This class instance
   */
  public function enableDottedNotation();

  /**
   * Disables dotted notation
   * 
   * @since 1.0.0
   * 
   * @return CollectionInterface This class instance
   */
  public function disableDottedNotation();

  /**
   * Overrides the default path separator
   * 
   * @since 1.0.0
   * 
   * @param string $separator
   * @return CollectionInterface This class instance
   */
  public function setPathSeparator($separator);

  /**
   * Removes all data
   *
   * @since 1.0.0
   * 
   * @return CollectionInterface This class instance
   */
  public function clear();

  /**
   * Sets a value for the given path
   *
   * @since 1.0.0
   * 
   * @param string $path Path where the value should be stored
   * @param mixed $value Value that should be stored
   * @return CollectionInterface This class instance
   */
  public function set($path, $value = true);

  /**
   * Sets a list of values
   * 
   * @since 1.0.0
   * 
   * @param array $values
   * @return CollectionInterface This class instance
   */
  public function setList(array $values);

  /**
   * Adds items to the target path
   * 
   * @since 1.0.0
   * 
   * @param string $path Target path
   * @param mixed $values Key/Values pairs to be added
   * @return CollectionInterface This class instance
   */
  public function add($path, $values);

  /**
   * Removed the first value from the indexed array
   * with a given $path or directly from the $data indexed array
   * 
   * @since 1.0.0
   * 
   * @param string $path Target array path
   * @return mixed The removed value or null
   */
  public function shift($path = null);

  /**
   * Adds a single item to the beginning of the target container array
   *  
   * @since 1.0.0
   * 
   * @param mixed $value Value to be inserted
   * @param string $path Optional path to unshift the value to
   * @return CollectionInterface This class instance
   */
  public function unshift($value, $path = null);

  /**
   * Adds a list of values to the beginning of the target container array
   * 
   * @since 1.0.0
   * 
   * @param array $values List of valus to be inserted
   * @param string $path Optional path to unshift values to
   * @return CollectionInterface This class instance
   */
  public function unshiftList(array $values, $path = null);

  /**
   * Adds a value to a given path or to the $data indexed array
   *
   * @since 1.0.0
   * 
   * @param mixed $values Values to be inserted
   * @param string $path Optional path to push the value to
   * @return CollectionInterface This class instance
   */
  public function push($value, $path = null);

  /**
   * Pushes list of values to target path
   * 
   * @since 1.0.0
   * 
   * @param array $values List of values to be pushed
   * @param string $path Optional path to push values to
   * @return CollectionInterface This class instance
   */
  public function pushList(array $values, $path = null);

  /**
   * Removes a value from a given path or 
   * from the $data indexed array
   *
   * @since 1.0.0
   * 
   * @param string $value Value to be popped 
   * @param string $path  Optional path to pop the value from
   * @return CollectionInterface This class instance
   */
  public function pop($value, $path = null);

  /**
   * Pops a list of values
   * 
   * @since 1.0.0
   * 
   * @param array $values List of values to be popped
   * @param string $path Optional path to pop values from
   * @return CollectionInterface This class instance
   */
  public function popList(array $values, $path = null);

  /**
   * Removes a single path
   * 
   * @since 1.0.0
   * 
   * @param string|array $path Path to be removed
   * @return CollectionInterface This class instance
   */
  public function remove($path);

 /**
   * Removes a list of paths
   * 
   * @since 1.0.0
   * 
   * @param array $paths List of paths to be removed
   * @return CollectionInterface This class instance
   */
  public function removeList(array $paths);

  /**
   * Gets value for target path
   *
   * @since 1.0.0
   * 
   * @param string|array $path 
   * @return mixed               
   */
  public function get($path);

  /**
   * Returns values for target paths
   * 
   * @since 1.0.0
   * 
   * @param array $paths Target paths
   * @return array List of existing results
   */
  public function getList(array $paths);

  /**
   * Returns all data
   * 
   * @since 1.0.0
   * 
   * @return array All data currently stored
   */
  public function getAll();

  /**
   * Get list of keys
   *
   * @since 1.0.0
   * 
   * @param boolean $with_value Optionally only return keys with value
   * @return array Indexed array with keys
   */
  public function getKeys($path = null);

  /**
   * Checks if the provided $path have an exact match
   * 
   * @since 1.0.0
   * 
   * @param string $path Path to search for
   * @return boolean True if the $key exists and false if not
   */
  public function hasKey($path);

  /**
   * Checks if the provided $value have an exact match
   * 
   * @since 1.0.0
   * 
   * @param mixed $value Value to search for
   * @param string $path Optional path to be searched for the given $value
   * @return boolean True if the value was found and false if not
   */
  public function hasValue($value, $path = null);

  /**
   * Counts the number of items
   * 
   * @since 1.0.0
   * 
   * @param boolean $path Optional path to look for and count items
   * @return integer Number of items contained
   */
  public function count($path = false);

  /**
   * Returns data as an ArrayIterator instance
   * 
   * @since 1.0.0
   * 
   * @return \ArrayIterator
   */
  public function getIterator();
}
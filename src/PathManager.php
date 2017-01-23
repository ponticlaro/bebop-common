<?php

namespace Ponticlaro\Bebop\Common;

class PathManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

  /**
   * List of environments
   * 
   * @var array;
   */
  protected $paths = [];

  /**
   * Instantiates Env Manager object
   * 
   */
  protected function __construct()
  {
    $uploads_data = wp_upload_dir();
    $template_dir = get_template_directory();

    // Adde default paths
    $this->paths['root']    = ABSPATH;
    $this->paths['admin']   = '';
    $this->paths['plugins'] = '';
    $this->paths['content'] = '';
    $this->paths['uploads'] = $uploads_data['basedir'];
    $this->paths['themes']  = str_replace('/'. basename($template_dir), '', $template_dir);
    $this->paths['theme']   = get_template_directory();
  }

  /**
   * Used to store a single path using a key
   * 
   * @param string $key  Key 
   * @param string $path Path
   */
  public function set($key, $path)
  {
    $this->paths[$key] = rtrim($path, '/');

    return $this;
  }

  /**
   * Checks if the target path exists
   * 
   * @param string $key Key of the path to check
   */
  public function has($key)
  {
    if (!is_string($key)) 
      return false;

    return isset($this->paths[$key]);
  }

  /**
   * Returns a single path using a key
   * with an optionally suffixed realtive path
   * 
   * @param  string $key           Key for the target path
   * @param  string $relative_path Optional relative path
   * @return string                path
   */
  public function get($key, $relative_path = null)
  {   
    if (!is_string($key))
      return null;

    if (!$this->has($key))
      return null;

    // Get path without trailing slash
    $path = $this->paths[$key];

    // Concatenate relative URL
    if ($relative_path)
      $path = rtrim($path, '/') .'/'. ltrim($relative_path, '/');

    return $path; 
  }

  /**
   * Returns all paths
   * 
   * @return array
   */
  public function getAll()
  {
    return $this->paths;
  }
}
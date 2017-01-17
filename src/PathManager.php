<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;

class PathManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

  /**
   * List of environments
   * 
   * @var Ponticlaro\Bebop\Common\Collection;
   */
  protected $__paths;

  /**
   * Instantiates Env Manager object
   * 
   */
  protected function __construct()
  {
    $uploads_data = wp_upload_dir();
    $template_dir = get_template_directory();

    // Instantiate paths collection object
    $this->__paths = new Collection(array(
      'root'    => ABSPATH,
      'admin'   => '',
      'plugins' => '',
      'content' => '',
      'uploads' => $uploads_data['basedir'],
      'themes'  => str_replace('/'. basename($template_dir), '', $template_dir),
      'theme'   => get_template_directory()
    ));
  }

  /**
   * Used to store a single path using a key
   * 
   * @param string $key  Key 
   * @param string $path Path
   */
  public function set($key, $path)
  {
    $this->__paths->set($key, rtrim($path, '/'));

    return $this;
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
    // Get path without trailing slash
    $path = $this->__paths->get($key);

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
    return $this->__paths->getAll();
  } 

  /**
   * Sends all undefined method calls to the paths collection object
   * 
   * @param  string $name Method name
   * @param  array  $args Method arguments
   * @return mixed        Method returned value
   */
  public function __call($name, $args)
  {
    if (!method_exists($this->__paths, $name))
      throw new \Exception("PathManager->$name method do not exist", 1);

    return call_user_func_array(array($this->__paths, $name), $args);
  }
}
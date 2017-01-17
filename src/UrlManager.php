<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;

class UrlManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

  /**
   * List of environments
   * 
   * @var Ponticlaro\Bebop\Common\Collection;
   */
  protected $__urls;

  /**
   * Instantiates Env Manager object
   * 
   */
  protected function __construct()
  {
    $uploads_data = wp_upload_dir();
    $template_url = get_bloginfo('template_url');

    // Instantiate paths collection object
    $this->__urls = new Collection(array(
      'home'    => home_url(),
      'admin'   => admin_url(),
      'plugins' => plugins_url(),
      'content' => content_url(),
      'uploads' => $uploads_data['baseurl'],
      'themes'  => str_replace('/'. basename($template_url), '', $template_url),
      'theme'   => $template_url
    ));
  }

  /**
   * Used to store a single URL using a key
   * 
   * @param string $key Key 
   * @param string $url URL
   */
  public function set($key, $url)
  {
    $this->__urls->set($key, rtrim($url, '/'));

    return $this;
  }

  /**
   * Returns a single URL using a key
   * with an optionally suffixed realtive URL
   * 
   * @param  string $key          Key for the target URL
   * @param  string $relative_url Optional relative URL
   * @return string               URL
   */
  public function get($key, $relative_url = null)
  {   
    // Get URL without trailing slash
    $url = $this->__urls->get($key);

    // Concatenate relative URL
    if ($relative_url)
      $url = rtrim($url, '/') .'/'. ltrim($relative_url, '/');

    return $url; 
  }

  /**
   * Returns all urls
   * 
   * @return array
   */
  public function getAll()
  {
    return $this->__urls->getAll();
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
    if (!method_exists($this->__urls, $name))
      throw new \Exception("UrlManager->$name method do not exist", 1);

    return call_user_func_array(array($this->__urls, $name), $args);
  }
}
<?php

namespace Ponticlaro\Bebop\Common;

class UrlManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

	/**
	 * Class instance
	 * 
	 * @var object
	 */
	private static $instance;

  /**
   * List of environments
   * 
   * @var array
   */
  protected $urls = [];

  /**
	 * Instantiates class
	 * 
	 * @return void
	 */
  public function __construct()
  {
    $uploads_data = wp_upload_dir();
    $template_url = get_bloginfo('template_url');

    // Instantiate paths collection object
    $this->urls['home']    = home_url();
    $this->urls['admin']   = admin_url();
    $this->urls['plugins'] = plugins_url();
    $this->urls['content'] = content_url();
    $this->urls['uploads'] = $uploads_data['baseurl'];
    $this->urls['themes']  = str_replace('/'. basename($template_url), '', $template_url);
    $this->urls['theme']   = $template_url;
  }

  /**
	 * Do not allow clones
	 * 
	 * @return void
	 */
  private final function __clone() {}

	/**
	 * Gets single instance of called class
	 * 
	 * @return object
	 */
	public static function getInstance() 
	{
		if (!isset(static::$instance))
      static::$instance = new static();

    return static::$instance;
	}

  /**
   * Used to store a single URL using a key
   * 
   * @param string $key Key 
   * @param string $url URL
   */
  public function set($key, $url)
  {
    $this->urls[$key] = rtrim($url, '/');

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

    return isset($this->urls[$key]);
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
    if (!is_string($key))
      return null;

    if (!$this->has($key))
      return null;

    // Get url without trailing slash
    $url = $this->urls[$key];

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
    return $this->urls;
  }
}
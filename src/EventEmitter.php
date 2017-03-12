<?php

namespace Ponticlaro\Bebop\Common;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;
use \Ponticlaro\Bebop\Common\Patterns\EventMessageInterface;

class EventEmitter implements EventEmitterInterface {

  /**
   * Class instance
   * 
   * @var object
   */
  private static $instance;

  /**
   * Event channels & subscribers
   * 
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $channels = [];

  /**
   * Instantiates class
   * 
   * @return void
   */
  public function __construct() {}

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
   * Subscribe a handler to a channel.
   *
   * @param string   $channel
   * @param callable $handler
   */
  public function subscribe($channel, callable $handler)
  {
    if (!isset($this->channels[$channel]))
      $this->channels[$channel] = [];

    $this->channels[$channel][] = $handler;

    return $this;
  }

  /**
   * Publish a message to a channel.
   *
   * @param string $channel
   * @param mixed  $message
   */
  public function publish($channel, EventMessageInterface $message)
  {
    foreach ($this->getChannelSubscribers($channel) as $handler) {
      call_user_func($handler, $message);
    }

    return $this;
  }
  
  /**
   * Returns all channels and their subscribers
   * 
   * @return array List of channels and their subscribers
   */
  public function getAllChannels()
  {
    return $this->channels;
  }

  /**
   * Return all subscribers on the given channel.
   *
   * @param string $channel
   * @return array
   */
  protected function getChannelSubscribers($channel)
  {
    return isset($this->channels[$channel]) ? $this->channels[$channel] : [];
  }
}
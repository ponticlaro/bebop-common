<?php

namespace Ponticlaro\Bebop\Common;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;
use \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract;

class EventEmitter extends SingletonAbstract implements EventEmitterInterface {

  /**
   * Event channels & subscribers
   * 
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $channels = [];

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
  public function publish($channel, $message)
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
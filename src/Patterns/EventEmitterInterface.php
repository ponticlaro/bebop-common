<?php

namespace Ponticlaro\Bebop\Common\Patterns;

interface EventEmitterInterface {

  /**
   * Subscribe a handler to a channel.
   *
   * @param string   $channel
   * @param callable $handler
   */
  public function subscribe($channel, callable $handler);

  /**
   * Publish a message to a channel.
   *
   * @param string $channel
   * @param mixed  $message
   */
  public function publish($channel, EventMessageInterface $message);
  
  /**
   * Returns all channels and their subscribers
   * 
   * @return array List of channels and their subscribers
   */
  public function getAllChannels();
}
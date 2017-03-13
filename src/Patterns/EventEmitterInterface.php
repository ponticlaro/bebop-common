<?php

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Event emitter interface.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @internal
 */
interface EventEmitterInterface {

  /**
   * Subscribe a handler to a channel.
   *
   * @since 1.1.4
   * 
   * @param string $channel
   * @param callable $handler
   */
  public function subscribe( $channel, callable $handler );

  /**
   * Publish a message to a channel.
   *
   * @since 1.1.4
   * 
   * @param string $channel
   * @param mixed $message
   */
  public function publish( $channel, EventMessageInterface $message );
  
  /**
   * Returns all channels and their subscribers.
   * 
   * @since 1.1.4
   * 
   * @return array List of channels and their subscribers
   */
  public function getAllChannels();
}
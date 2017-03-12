<?php
/**
 * EventEmitter class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;
use Ponticlaro\Bebop\Common\Patterns\EventMessageInterface;
use Ponticlaro\Bebop\Common\Patterns\SingletonTrait;

/**
 * Event emitter class.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @since 1.1.5 Uses SingletonTrait trait
 * @internal
 * @see \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface Implemented EventEmitter interface
 */
class EventEmitter implements EventEmitterInterface {

  use SingletonTrait;

  /**
   * Event channels & subscribers.
   * 
   * @var Ponticlaro\Bebop\Common\Collection object
   */
  protected $channels = [];

  /**
   * Instantiates class.
   * 
   * @return void
   */
  public function __construct() {}

  /**
   * Subscribe a handler to a channel.
   *
   * @param string   $channel
   * @param callable $handler
   */
  public function subscribe( $channel, callable $handler )
  {
    if ( ! isset( $this->channels[ $channel ] ) )
      $this->channels[ $channel ] = [];

    $this->channels[ $channel ][] = $handler;

    return $this;
  }

  /**
   * Publish a message to a channel.
   *
   * @param string $channel
   * @param mixed $message
   * @return EventEmitter This class instance
   */
  public function publish( $channel, EventMessageInterface $message )
  {
    foreach ( $this->getChannelSubscribers( $channel ) as $handler ) {
      call_user_func( $handler, $message );
    }

    return $this;
  }
  
  /**
   * Returns all channels and their subscribers.
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
  protected function getChannelSubscribers( $channel )
  {
    return isset( $this->channels[ $channel ] ) ? $this->channels[ $channel ] : [];
  }
}
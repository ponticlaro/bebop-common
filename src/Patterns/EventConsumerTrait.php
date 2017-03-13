<?php

namespace Ponticlaro\Bebop\Common\Patterns;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;

/**
 * Event consumer trait.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @internal
 */
trait EventConsumerTrait {  

  /**
   * Events emitter instance.
   * 
   * @since 1.1.4
   * 
   * @var EventEmitterInterface object
   */
  protected $__event_emitter;

  /**
   * Sets event emitter object.
   * 
   * @since 1.1.4
   * 
   * @param EventEmitterInterface $event_emitter Event emitter instance
   */
  public function setEventEmitter( EventEmitterInterface $event_emitter )
  {
    $this->__event_emitter = $event_emitter;

    return $this;
  }

  /**
   * Returns event emitter instance.
   * 
   * @since 1.1.4
   * 
   * @return EventEmitterInterface object Event emitter instance
   */
  public function getEventEmitter()
  {
    return $this->__event_emitter;
  }
}
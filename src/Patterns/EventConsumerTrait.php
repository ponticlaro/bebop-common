<?php

namespace Ponticlaro\Bebop\Common\Patterns;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;

trait EventConsumerTrait {  

  /**
   * Events emitter instance
   * 
   * @var EventEmitterInterface object
   */
  protected $__event_emitter;

  /**
   * Sets event emitter object
   * 
   * @param EventEmitterInterface $event_emitter Event emitter instance
   */
  public function setEventEmitter(EventEmitterInterface $event_emitter)
  {
    $this->__event_emitter = $event_emitter;

    return $this;
  }

  /**
   * Returns event emitter instance
   * 
   * @return EventEmitterInterface object Event emitter instance
   */
  public function getEventEmitter()
  {
    return $this->__event_emitter;
  }
}
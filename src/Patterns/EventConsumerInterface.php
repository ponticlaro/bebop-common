<?php

namespace Ponticlaro\Bebop\Common\Patterns;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;

interface EventConsumerInterface {  

  /**
   * Sets event emitter object
   * 
   * @param EventEmitterInterface $event_emitter Event emitter instance
   */
  public function setEventEmitter(EventEmitterInterface $event_emitter);

  /**
   * Returns event emitter instance
   * 
   * @return EventEmitterInterface object Event emitter instance
   */
  public function getEventEmitter();

  /**
   * Consumes event
   * 
   * @param mixed $message Event message
   */
  public function consumeEvent($message);
}
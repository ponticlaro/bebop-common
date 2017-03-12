<?php
/**
 * EventConsumer interface.
 *
 * @package Bebop\Common
 * @since 1.1.4
 */

namespace Ponticlaro\Bebop\Common\Patterns;

use \Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;
use \Ponticlaro\Bebop\Common\Patterns\EventMessageInterface;

/**
 * Event consumer interface.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @internal
 */
interface EventConsumerInterface {  

  /**
   * Sets event emitter object.
   * 
   * @since 1.1.4
   * 
   * @param EventEmitterInterface $event_emitter Event emitter instance
   */
  public function setEventEmitter( EventEmitterInterface $event_emitter );

  /**
   * Returns event emitter instance.
   * 
   * @since 1.1.4
   * 
   * @return EventEmitterInterface Event emitter instance
   */
  public function getEventEmitter();

  /**
   * Consumes event.
   * 
   * @since 1.1.4
   * 
   * @param mixed $message Event message
   */
  public function consumeEvent( EventMessageInterface $message );
}
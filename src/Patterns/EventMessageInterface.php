<?php
/**
 * EventMessage interface.
 *
 * @package Bebop\Common
 * @since 1.1.4
 */

namespace Ponticlaro\Bebop\Common\Patterns;

/**
 * Event message transport interface.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @internal
 */
interface EventMessageInterface {  

  /**
   * Instantiates class.
   * 
   * @since 1.1.4
   * 
   * @param string $action Message action ID
   * @param array $data Message data
   */
  public function __construct( $action, array $data = [] );

  /**
   * Sets message action ID.
   * 
   * @since 1.1.4
   * 
   * @param string $action Action ID
   * @return EventMessageInterface This class instance
   */
  public function setAction( $action );

  /**
   * Returns message action ID.
   * 
   * @since 1.1.4
   * 
   * @return string Message action
   */
  public function getAction();

  /**
   * Sets message data.
   * 
   * @since 1.1.4
   * 
   * @param array $data Message data
   * @return EventMessageInterface This class instance
   */
  public function setData( array $data = [] );

  /**
   * Returns message data
   * 
   * @since 1.1.4
   * 
   * @return array Message data
   */
  public function getData();
}
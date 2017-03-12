<?php
/**
 * EventMessage class.
 *
 * @package Bebop\Common
 * @since 1.1.4
 */

namespace Ponticlaro\Bebop\Common;

use \Ponticlaro\Bebop\Common\Patterns\EventMessageInterface;

/**
 * Event message transport class.
 *
 * @package Bebop\Common
 * @since 1.1.4
 * @internal
 * @see \Ponticlaro\Bebop\Common\Patterns\EventMessageInterface Implemented EventMessage interface
 */
class EventMessage implements EventMessageInterface {

  /**
   * Message action ID.
   * 
   * @since 1.1.4
   * 
   * @var string
   */
  protected $action;

  /**
   * Message data.
   * 
   * @since 1.1.4
   * 
   * @var array
   */
  protected $data;

  /**
   * {@inheritDoc}
   */
  public function __construct( $action, array $data = [] )
  {
    if ( ! is_string( $action ) )
      throw new \Exception( 'EventMessage $action must be a string' );

    $this->action = $action;
    $this->data   = $data;
  }

  /**
   * {@inheritDoc}
   */
  public function setAction( $action )
  {
    if ( ! is_string( $action ) )
      throw new \Exception( 'EventMessage $action must be a string' );

    $this->action = $action;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * {@inheritDoc}
   */
  public function setData( array $data = [] )
  {
    $this->data = $data;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getData()
  {
    return $this->data;
  }
}
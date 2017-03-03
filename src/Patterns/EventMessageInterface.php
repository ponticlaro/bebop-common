<?php

namespace Ponticlaro\Bebop\Common\Patterns;

interface EventMessageInterface {  

  /**
   * Instantiates class
   * 
   * @param string $action Message action ID
   * @param array  $data   Message data
   */
  public function __construct($action, array $data = []);

  /**
   * Sets message action ID
   * 
   * @param string $action Action ID
   */
  public function setAction($action);

  /**
   * Returns message action ID
   * 
   */
  public function getAction();

  /**
   * Sets message data
   * 
   * @param array $data Message data
   */
  public function setData(array $data = []);

  /**
   * Returns message data
   * 
   */
  public function getData();
}
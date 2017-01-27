<?php

namespace Ponticlaro\Bebop\Common\Patterns;

interface TrackableObjectInterface {

  /**
   * Returns trackable object ID
   * 
   * @return string
   */
	public function getObjectID();

  /**
   * Returns trackable object type
   * 
   * @return string
   */
	public function getObjectType();
}
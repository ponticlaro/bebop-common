<?php

use Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait;
use Ponticlaro\Bebop\Common\Patterns\FactoryAbstract;
use Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract;

function context_container_callable($wp_query) {
  return true;
}

function event_emitter_subscriber($message) {

}

class TestEventConsumer {
  
  use EventConsumerTrait;
}

class TestTrackableObject extends TrackableObjectAbstract {

  protected $__trackable_id   = 'test_one';
  protected $__trackable_type = 'test';
}

class TestManufacturableOne {}
class TestManufacturableTwo {}
class TestManufacturableThree {}
class TestManufacturableFour {}
class TestManufacturableOneReplacement {}

class TestFactory extends FactoryAbstract {

  protected static $manufacturable = [
    'one'   => 'TestManufacturableOne',
    'two'   => 'TestManufacturableTwo',
    'three' => 'TestManufacturableThree',
  ];
}
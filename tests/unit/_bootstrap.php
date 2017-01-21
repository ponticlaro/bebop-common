<?php

use Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait;
use Ponticlaro\Bebop\Common\Patterns\FactoryAbstract;
use Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract;

function context_container_callable($wp_query) {
  return true;
}

function event_emitter_subscriber($message) {

}

function utils_sample_control_elements_html() {

  echo '
  <input type="text" name="text">
  <input type="hidden" name="hidden">
  <input type="checkbox" name="checkbox">
  <input type="checkbox" name="multiple_checkboxes[]">
  <input type="checkbox" name="multiple_checkboxes[]">
  <input type="radio" name="radio">
  <input type="radio" name="multiple_radios">
  <input type="radio" name="multiple_radios">
  <input type="file" name="file">
  <select name="select"></select>
  <select multiple="multiple" name="select_multiple"></select>
  <textarea name="textarea"></textarea>
  ';
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
<?php

use Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait;

function context_container_callable($wp_query) {
  return true;
}

class TestEventConsumer {
  use EventConsumerTrait;
}

function event_emitter_subscriber($message) {

}

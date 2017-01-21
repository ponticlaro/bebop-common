<?php

namespace Patterns;

use AspectMock\Test;
use Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait;
use Ponticlaro\Bebop\Common\Patterns\EventEmitterInterface;
use UnitTester;

class EventConsumerTraitCest
{
  public function _before(UnitTester $I)
  {
    Test::clean();
  }

  public function _after(UnitTester $I)
  {
    Test::clean();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait::setEventEmitter
   * @covers Ponticlaro\Bebop\Common\Patterns\EventConsumerTrait::getEventEmitter
   * 
   * @param UnitTester $I Tester Module
   */
  public function setAndGetEventEmitter(UnitTester $I)
  {
    $emitter_mock = Test::double('Ponticlaro\Bebop\Common\EventEmitter');
    $emitter      = $emitter_mock->make()->getInstance();

    $consumer = new \TestEventConsumer;
    $consumer->setEventEmitter($emitter);

    $I->assertEquals($emitter, $consumer->getEventEmitter());
  }
}

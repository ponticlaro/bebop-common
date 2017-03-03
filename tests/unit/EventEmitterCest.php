<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\EventEmitter;

class EventEmitterCest
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
   * @covers Ponticlaro\Bebop\Common\EventEmitter::subscribe
   * @covers Ponticlaro\Bebop\Common\EventEmitter::publish
   * @covers Ponticlaro\Bebop\Common\EventEmitter::getAllChannels
   * @covers Ponticlaro\Bebop\Common\EventEmitter::getChannelSubscribers
   * 
   * @param UnitTester $I Tester Module
   */
  public function PubSubAndGetChannels(UnitTester $I)
  {
    // Create EventMessage
    $message = new \Ponticlaro\Bebop\Common\EventMessage('unit_test_action', []);

    // Mock function to be used as subscriber
    $subscriber = Test::func('Ponticlaro\Bebop\Common', 'event_emitter_subscriber', null);

    $src_channels = [
      'one'   => [
        $subscriber
      ],
      'two'   => [
        $subscriber,
        $subscriber,
      ],
      'three' => [
        $subscriber,
        $subscriber,
        $subscriber,
      ]
    ];

    $ee = EventEmitter::getInstance();

    // Testing ::subscribe
    foreach ($src_channels as $src_channel => $subscribers) {
      foreach ($subscribers as $subscriber) {
        $ee->subscribe($src_channel, $subscriber);
      }
    }

    // Testing ::getAllChannels
    $channels = $ee->getAllChannels();

    $I->assertEquals($src_channels, $ee->getAllChannels());

    // Testing ::publish
    foreach ($channels as $channel => $subscribers) {
      $ee->publish($channel, $message);
    }

    $subscriber->verifyInvokedMultipleTimes(6, [$message]);
  }
}

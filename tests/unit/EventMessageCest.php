<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\EventMessage;

class EventMessageCest
{
  public function _before(UnitTester $I)
  {

  }

  public function _after(UnitTester $I)
  {
    Test::clean();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EventMessage::__construct
   * 
   * @param UnitTester $I Tester Module
   */
  public function create(UnitTester $I)
  {
    // Test ::__construct with bad arguments
    $bad_args = [
      null, false, true, 0, 1, [1], new \stdClass
    ];

    foreach ($bad_args as $bad_arg_val) {

      // Check if exception is thrown with bad arguments
      $I->expectException(Exception::class, function() use($bad_arg_val) {
        new EventMessage($bad_arg_val, []);
      });
    }  
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EventMessage::__construct
   * @covers Ponticlaro\Bebop\Common\EventMessage::getAction
   * @covers Ponticlaro\Bebop\Common\EventMessage::setAction
   * 
   * @param UnitTester $I Tester Module
   */
  public function getAndSetAction(UnitTester $I)
  {
    // Create test instance
    $message = new EventMessage('unit_test_action', []);

    // Get ::getAction default value
    $I->assertEquals($message->getAction(), 'unit_test_action');

    // Test ::setAction
    $message->setAction('unit_test_action_updated');

    // Get ::getAction updated value
    $I->assertEquals($message->getAction(), 'unit_test_action_updated');

    // Test ::setAction with bad arguments
    $bad_args = [
      null, false, true, 0, 1, [1], new \stdClass
    ];

    foreach ($bad_args as $bad_arg_val) {

      // Check if exception is thrown with bad arguments
      $I->expectException(Exception::class, function() use($message, $bad_arg_val) {
        $message->setAction($bad_arg_val);
      });
    }  
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EventMessage::__construct
   * @covers Ponticlaro\Bebop\Common\EventMessage::getData
   * @covers Ponticlaro\Bebop\Common\EventMessage::setData
   * 
   * @param UnitTester $I Tester Module
   */
  public function getAndSetData(UnitTester $I)
  {
    // Create test instance
    $message = new EventMessage('unit_test_action', [
      'key_1' => 'value_1',
      'key_2' => 'value_2',
    ]);

    // Get ::getData default value
    $I->assertEquals($message->getData(), [
      'key_1' => 'value_1',
      'key_2' => 'value_2',
    ]);

    // Test ::setData
    $message->setData([
      'key_3' => 'value_3',
      'key_4' => 'value_4',
    ]);

    // Get ::getData updated value
    $I->assertEquals($message->getData(), [
      'key_3' => 'value_3',
      'key_4' => 'value_4',
    ]);
  }
}

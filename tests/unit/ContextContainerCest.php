<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\ContextContainer;

class ContextContainerCest
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
   * @covers Ponticlaro\Bebop\Common\ContextContainer::__construct
   * @covers Ponticlaro\Bebop\Common\ContextContainer::getId
   * @covers Ponticlaro\Bebop\Common\ContextContainer::getFunction
   * @covers Ponticlaro\Bebop\Common\ContextContainer::run
   * 
   * @param UnitTester $I Tester Module
   */
  public function create(UnitTester $I)
  {
    // Mock is_string; Force it to return true
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', true);

    // Mock function to be used as subscriber
    $callable = Test::func('Ponticlaro\Bebop\Common', 'context_container_callable', null);

    // Create test instance
    $container = new ContextContainer('test', $callable);

    // Check if is_string was called once
    $is_string_mock->verifyInvokedOnce(['test']);

    // Testing ::getId and ::getFunction
    $I->assertEquals('test', $container->getId());
    $I->assertEquals($callable, $container->getFunction());

    // Global needed for ::run
    global $wp_query;

    $wp_query = new stdClass;

    // Testing ::run
    $container->run();

    // Check if call_user_func_array was called once
    $callable->verifyInvokedOnce([$wp_query]);

    // Reset test
    Test::clean();

    // Mock is_string
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', false);

    // Check if exception is thrown with bad arguments
    $I->expectException(Exception::class, function() use($callable) {
      new ContextContainer(null, $callable);
    });

    $is_string_mock->verifyInvokedOnce([null]);
  }
}

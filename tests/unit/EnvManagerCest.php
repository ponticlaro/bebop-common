<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\Env;
use Ponticlaro\Bebop\Common\EnvManager;

class EnvManagerCest
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
   * @covers Ponticlaro\Bebop\Common\EnvManager::__construct
   * @covers Ponticlaro\Bebop\Common\EnvManager::exists
   * @covers Ponticlaro\Bebop\Common\EnvManager::get
   * 
   * @param UnitTester $I Tester Module
   */
  public function createAndExistsGet(UnitTester $I)
  {
    // Create test instance
    $m = EnvManager::getInstance();

    // Testing ::exists and ::get
    $I->assertTrue($m->exists('development'));
    $I->assertTrue($m->get('development') instanceof Env);

    $I->assertTrue($m->exists('staging'));
    $I->assertTrue($m->get('staging') instanceof Env);

    $I->assertTrue($m->exists('production'));
    $I->assertTrue($m->get('production') instanceof Env);

    // Further ::exists testing
    $I->assertFalse($m->exists(null));
    $I->assertFalse($m->exists('new_env'));

    // Further ::get testing
    $I->assertNull($m->get(null));
    $I->assertNull($m->get('new_env'));
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EnvManager::add
   * @depends createAndExistsGet
   * 
   * @param UnitTester $I Tester Module
   */
  public function add(UnitTester $I)
  {
    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Env
    $env_mock = Test::double('Ponticlaro\Bebop\Common\Env', [
      '__construct' => null // Making sure new Env do not create a Collection
    ]);

    // Create test instance
    $m = EnvManager::getInstance();

    // Testing ::add
    $m->add('testing');

    // Check if collection::set was invoked
    $coll_mock->verifyInvokedOnce('set');

    // Check if an env was created
    $env_mock->verifyInvokedOnce('__construct');

    $I->assertTrue($m->exists('testing'));
    $I->assertTrue($m->get('testing') instanceof Env);

    Test::clean();

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Env
    $env_mock = Test::double('Ponticlaro\Bebop\Common\Env', [
      '__construct' => null // Making sure new Env do not create a Collection
    ]);

    // Testing if ::add does not replace existing env
    $existing_env = $m->get('production');
    $added_env    = $m->add('production')->get('production');

    // Check if collection::set was not invoked
    $coll_mock->verifyNeverInvoked('set');

    // Check if an env was not created
    $env_mock->verifyNeverInvoked('__construct');

    $I->assertSame($existing_env, $added_env);

    Test::clean();

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Env
    $env_mock = Test::double('Ponticlaro\Bebop\Common\Env', [
      '__construct' => null // Making sure new Env do not create a Collection
    ]);

    // Testing ::add with bad arguments
    $m->add(null);

    // Check if collection::set was not invoked
    $coll_mock->verifyNeverInvoked('set');

    // Check if an env was not created
    $env_mock->verifyNeverInvoked('__construct');
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EnvManager::replace
   * @depends createAndExistsGet
   * @depends add
   * 
   * @param UnitTester $I Tester Module
   */
  public function replace(UnitTester $I)
  {
    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Env
    $env_mock = Test::double('Ponticlaro\Bebop\Common\Env', [
      '__construct' => null // Making sure new Env do not create a Collection
    ]);

    // Create test instance
    $m = EnvManager::getInstance();

    // Testing ::replace
    $existing_env    = $m->get('testing');
    $replacement_env = $m->replace('testing')->get('testing');

    // Check if collection::set was invoked
    $coll_mock->verifyInvokedOnce('set');

    // Check if an env was created
    $env_mock->verifyInvokedOnce('__construct');

    // Check if the target env was replaced
    $I->assertNotSame($existing_env, $replacement_env);

    Test::clean();

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Env
    $env_mock = Test::double('Ponticlaro\Bebop\Common\Env', [
      '__construct' => null // Making sure new Env do not create a Collection
    ]);

    // Testing ::replace with bad arguments
    $m->replace(null);

    // Check if collection::set was not invoked
    $coll_mock->verifyNeverInvoked('set');

    // Check if an env was not created
    $env_mock->verifyNeverInvoked('__construct');
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EnvManager::remove
   * @depends createAndExistsGet
   * @depends add
   * @depends replace
   * 
   * @param UnitTester $I Tester Module
   */
  public function remove(UnitTester $I)
  {
    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Create test instance
    $m = EnvManager::getInstance();

    // Testing ::remove
    $m->remove('testing');

    // Check if collection::remove was invoked
    $coll_mock->verifyInvokedOnce('remove');

    // Check if the target env was replaced
    $I->assertFalse($m->exists('testing'));

    Test::clean();

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Testing ::remove with bad arguments
    $m->remove(null);

    // Check if collection::set was not invoked
    $coll_mock->verifyNeverInvoked('remove');
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\EnvManager::is
   * @covers Ponticlaro\Bebop\Common\EnvManager::getCurrent
   * @covers Ponticlaro\Bebop\Common\EnvManager::getCurrentKey
   * @depends createAndExistsGet
   * @depends add
   * @depends replace
   * @depends remove
   * 
   * @param UnitTester $I Tester Module
   */
  public function checkCurrentEnv(UnitTester $I)
  {
    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Create test instance
    $m = EnvManager::getInstance();

    // Testing ::is, ::getCurrent and ::getCurrentKey without APP_ENV in place
    $I->assertTrue($m->is('development'));
    $I->assertSame($m->get('development'), $m->getCurrent());
    $I->assertEquals('development', $m->getCurrentKey());

    // Testing ::is, ::getCurrent and ::getCurrentKey with APP_ENV in place
    putenv('APP_ENV=staging');

    $I->assertTrue($m->is('staging'));
    $I->assertSame($m->get('staging'), $m->getCurrent());
    $I->assertEquals('staging', $m->getCurrentKey());

    // Testing ::is with env that does not exist
    $I->assertFalse($m->is('new_env'));

    // Testing ::is with bad arguments
    $I->assertFalse($m->is(null));
  }
}

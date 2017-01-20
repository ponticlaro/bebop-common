<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\Env;

class EnvCest
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
   * @covers Ponticlaro\Bebop\Common\Env::__construct
   * @covers Ponticlaro\Bebop\Common\Env::getKey
   * 
   * @param UnitTester $I Tester Module
   */
  public function createAndGetKey(UnitTester $I)
  {
    // Mock is_string; Force it to return true
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', true);

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Create test instance
    $env = new Env('testing');

   // Check if is_string was called once
    $is_string_mock->verifyInvokedOnce(['testing']);

    // Check if collection was created
    $coll_mock->verifyInvokedOnce('__construct');

    // Check if ::getKey works
    $I->assertEquals('testing', $env->getKey());

    // Reset test
    Test::clean();

    // Mock is_string; Force it to return false
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', false);

    // Check if exception is thrown with bad arguments
    $I->expectException(Exception::class, function() {
      new Env(null);
    });

    $is_string_mock->verifyInvokedOnce();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Env::addHost
   * @covers Ponticlaro\Bebop\Common\Env::addHosts
   * @covers Ponticlaro\Bebop\Common\Env::hasHost
   * @covers Ponticlaro\Bebop\Common\Env::getHosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function manageHosts(UnitTester $I)
  {
    $src_hosts = [
      'http://wp.local',
      'http://wp2.local',
      'http://wp3.local'
    ];

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Create test instance
    $env = new Env('testing');

    // Add Host
    $env->addHost($src_hosts[0]);

    // Add Hosts
    $env->addHosts([
      $src_hosts[1],
      $src_hosts[2]
    ]);

    // Check if hosts where added
    $I->assertTrue($env->hasHost($src_hosts[0]));
    $I->assertTrue($env->hasHost($src_hosts[1]));
    $I->assertTrue($env->hasHost($src_hosts[2]));
    $I->assertEquals($src_hosts, $env->getHosts());
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Env::isCurrent
   * 
   * @param UnitTester $I Tester Module
   */
  public function isCurrent(UnitTester $I)
  {
    // Create test instance
    $env = new Env('testing');

    // Check if returns false if no APP_ENV and hosts
    $I->assertFalse($env->isCurrent());

    // Check if env matches current APP_ENV
    putenv('APP_ENV=testing');
    $I->assertTrue($env->isCurrent());

    // Add Host
    $env->addHost('http://wp.local');

    // Check if env matches current host
    $_SERVER['SERVER_NAME'] = 'http://wp.local';

    $I->assertTrue($env->isCurrent());

    // Check if env doesn't match current host
    $_SERVER['SERVER_NAME'] = 'http://wp2.local';

    $I->assertFalse($env->isCurrent());
  }
}

<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\Feature;
use Ponticlaro\Bebop\Common\FeatureManager;

class FeatureManagerCest
{
  public function _before(UnitTester $I)
  {
      Test::clean();
      \WP_Mock::setUp();
  }

  public function _after(UnitTester $I)
  {
    Test::clean();
    \WP_Mock::tearDown();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\FeatureManager::getInstance
   * @covers Ponticlaro\Bebop\Common\FeatureManager::__construct
   * @covers Ponticlaro\Bebop\Common\FeatureManager::add
   * @covers Ponticlaro\Bebop\Common\FeatureManager::get
   * @covers Ponticlaro\Bebop\Common\FeatureManager::exists
   * 
   * @param UnitTester $I Tester Module
   */
  public function addGetExists(UnitTester $I)
  {
    $m = FeatureManager::getInstance();

    // Mock Feature
    $feat = Test::double(new Feature('test'))->getObject();

    $m->add($feat);

    $I->assertEquals($feat, $m->get('test'));
    $I->assertNull($m->get('test_2'));
    $I->assertTrue($m->exists('test'));
    $I->assertFalse($m->exists('test_2'));

    // Checking if ::exists return null on bad arguments
    $I->assertNull($m->exists(null));
    $I->assertNull($m->exists(1));
    $I->assertNull($m->exists([]));
    $I->assertNull($m->exists(new \stdClass));
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\FeatureManager::getAll
   * 
   * @param UnitTester $I Tester Module
   */
  public function getAll(UnitTester $I)
  {
    $features = [
      'test_1' => Test::double(new Feature('test_1'))->getObject(),
      'test_2' => Test::double(new Feature('test_2'))->getObject(),
      'test_3' => Test::double(new Feature('test_3'))->getObject(),
    ];

    $m = FeatureManager::getInstance();

    $m->clear();

    foreach ($features as $feature) {
      $m->add($feature);
    }

    $I->assertEquals($features, $m->getAll());
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\FeatureManager::clear
   * 
   * @param UnitTester $I Tester Module
   */
  public function clear(UnitTester $I)
  {
    $m = FeatureManager::getInstance();

    $I->assertEquals([], $m->clear()->getAll());
  }
}

<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\Feature;

class FeatureCest
{
  protected $fid = 'test';

  protected $fconfig = [
    'key_1' => 'value_1',
    'key_2' => [
      'value_1',
      'value_2'
    ]
  ];

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
   * @covers Ponticlaro\Bebop\Common\Feature::__construct
   * 
   * @param UnitTester $I Tester Module
   */
  public function create(UnitTester $I)
  {
    // Mock is_string
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', true);

    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    // Mock Feature
    $feat_mock = Test::double('Ponticlaro\Bebop\Common\Feature');

    // Create test instance
    $feat = new Feature($this->fid, $this->fconfig);

    // Check if is_string was called once
    $is_string_mock->verifyInvokedOnce([
      $this->fid
    ]);

    // Check if collection was created
    $coll_mock->verifyInvokedOnce('__construct');

    // Check if config elements were added
    $feat_mock->verifyInvokedMultipleTimes('set', 2);

    $I->assertEquals($this->fid, $feat->getId());
    $I->assertEquals($this->fconfig, $feat->getAll());

    // Reset test
    Test::clean();

    // Mock is_string
    $is_string_mock = Test::func('Ponticlaro\Bebop\Common', 'is_string', false);

    // Check if exception is thrown with bad arguments
    $I->expectException(Exception::class, function() {
      new Feature(null);
    });

    $is_string_mock->verifyInvokedOnce();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::__construct
   * 
   * @param UnitTester $I Tester Module
   */
  public function createWithException(UnitTester $I)
  {

  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::enable
   * @covers Ponticlaro\Bebop\Common\Feature::isEnabled
   * 
   * @param UnitTester $I Tester Module
   */
  public function enable(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $I->assertFalse($feat->isEnabled());

    $feat->enable();

    $I->assertTrue($feat->isEnabled());
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::disable
   * @covers Ponticlaro\Bebop\Common\Feature::isEnabled
   * 
   * @param UnitTester $I Tester Module
   */
  public function disable(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $feat->enable();

    $I->assertTrue($feat->isEnabled());

    $feat->disable();

    $I->assertFalse($feat->isEnabled());
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::getId
   * 
   * @param UnitTester $I Tester Module
   */
  public function getId(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $I->assertEquals($this->fid, $feat->getId());
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::set
   * @covers Ponticlaro\Bebop\Common\Feature::get
   * @covers Ponticlaro\Bebop\Common\Feature::has
   * 
   * @param UnitTester $I Tester Module
   */
  public function setGetHas(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);
   
    $value = $feat->set('key_3', 'value_3')->get('key_3');

    $I->assertEquals('value_3', $value);
    $I->assertTrue($feat->has('key_1'));
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Feature::getAll
   * 
   * @param UnitTester $I Tester Module
   */
  public function getAll(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $I->assertEquals($this->fconfig, $feat->getAll());
  }
}

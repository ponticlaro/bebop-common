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
   * Testing Feature::enable()
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
   * Testing Feature::disable()
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
   * Testing Feature::getId()
   * 
   * @param UnitTester $I Tester Module
   */
  public function getId(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $I->assertEquals($this->fid, $feat->getId());
  }

  /**
   * Testing Feature::set()
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
   * Testing Feature::getAll()
   * 
   * @param UnitTester $I Tester Module
   */
  public function getAll(UnitTester $I)
  {
    $feat = new Feature($this->fid, $this->fconfig);

    $I->assertEquals($this->fconfig, $feat->getAll());
  }
}

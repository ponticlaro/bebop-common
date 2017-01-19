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

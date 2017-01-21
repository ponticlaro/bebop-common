<?php

namespace Patterns;

use AspectMock\Test;
use TestFactory;
use UnitTester;

class FactoryAbstractCest
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
   * @covers Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::canManufacture
   * 
   * @param UnitTester $I Tester Module
   */
  public static function canManufacture(UnitTester $I)
  {
    $I->assertTrue(TestFactory::canManufacture('one'));
    $I->assertTrue(TestFactory::canManufacture('two'));
    $I->assertTrue(TestFactory::canManufacture('three'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::get
   * @depends canManufacture
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    $I->assertEquals('TestManufacturableOne', TestFactory::get('one'));
    $I->assertEquals('TestManufacturableTwo', TestFactory::get('two'));
    $I->assertEquals('TestManufacturableThree', TestFactory::get('three'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::create
   * 
   * @param UnitTester $I Tester Module
   */
  public function create(UnitTester $I)
  {
    $I->assertTrue(TestFactory::create('one') instanceof \TestManufacturableOne);
    $I->assertTrue(TestFactory::create('two') instanceof \TestManufacturableTwo);
    $I->assertTrue(TestFactory::create('three') instanceof \TestManufacturableThree);
    $I->assertNull(TestFactory::create('hundred'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::getInstanceId
   * @depends create
   * 
   * @param UnitTester $I Tester Module
   */
  public function getInstanceId(UnitTester $I)
  {
    $manuf_one   = TestFactory::create('one');
    $manuf_two   = TestFactory::create('two');
    $manuf_three = TestFactory::create('three');
    
    $I->assertEquals(TestFactory::getInstanceId($manuf_one), 'one');
    $I->assertEquals(TestFactory::getInstanceId($manuf_two), 'two');
    $I->assertEquals(TestFactory::getInstanceId($manuf_three), 'three');
    $I->assertNull(TestFactory::getInstanceId('not_an_object'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::set
   * @depends canManufacture
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    TestFactory::set('four', 'TestManufacturableFour');

    $I->assertTrue(TestFactory::canManufacture('four'));
    $I->assertTrue(TestFactory::create('four') instanceof \TestManufacturableFour);

    TestFactory::set('one', 'TestManufacturableOneReplacement');

    $I->assertTrue(TestFactory::canManufacture('one'));
    $I->assertTrue(TestFactory::create('one') instanceof \TestManufacturableOneReplacement);
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\Patterns\FactoryAbstract::remove
   * @depends set
   * 
   * @param UnitTester $I Tester Module
   */
  public function remove(UnitTester $I)
  {
    TestFactory::remove('four');

    $I->assertFalse(TestFactory::canManufacture('four'));
  }
}

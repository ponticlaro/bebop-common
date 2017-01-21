<?php

namespace Patterns;

use UnitTester;

class TrackableObjectAbstractCest
{
  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract::getObjectID
   * @covers Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract::getObjectType
   * 
   * @param UnitTester $I Tester Module
   */
  public function getIdAndType(UnitTester $I)
  {
    $trackable = new \TestTrackableObject;

    $I->assertEquals('test_one', $trackable->getObjectID());
    $I->assertEquals('test', $trackable->getObjectType());
  }
}

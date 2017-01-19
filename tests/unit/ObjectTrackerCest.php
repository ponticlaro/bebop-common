<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\ObjectTracker;
use Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract;

class ObjectTrackerCest
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
   * @covers Ponticlaro\Bebop\Common\ObjectTracker::track
   * @covers Ponticlaro\Bebop\Common\ObjectTracker::get
   * 
   * @param UnitTester $I Tester Module
   */
  public function trackGet(UnitTester $I)
  {
    // Mock TrackableObjectAbstract
    $trackable_mock = Test::double('Ponticlaro\Bebop\Common\Patterns\TrackableObjectAbstract', [
      'getObjectID'   => 'test_id',
      'getObjectType' => 'test_type'
    ]);

    // Get mocked TrackableObjectAbstract instance
    $src_trackable = $trackable_mock->make();

    // Track and get mocked TrackableObjectAbstract instance
    $trackable = ObjectTracker::getInstance()->track($src_trackable)->get('test_type', 'test_id');

    // Verify TrackableObjectAbstract methods are called
    $trackable_mock->verifyInvoked('getObjectID');
    $trackable_mock->verifyInvoked('getObjectType');

    // Verify the returned object matches the tracker one
    $I->assertEquals($src_trackable, $trackable);
  }
}

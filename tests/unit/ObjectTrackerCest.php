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
   * @author cristianobaptista
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
    $tracker   = ObjectTracker::getInstance();
    $trackable = $tracker->track($src_trackable)->get('test_type', 'test_id');

    // Verify TrackableObjectAbstract methods are called
    $trackable_mock->verifyInvoked('getObjectID');
    $trackable_mock->verifyInvoked('getObjectType');

    // Verify the returned object matches the tracker one
    $I->assertEquals($src_trackable, $trackable);

    // Check if ::get returns null if object id doesn't exist
    $tracker->get('test_type', 'test_id_2');

    // Check if ::get returns null if object type doesn't exist
    $tracker->get('test_type_2', 'test_id_2');

    // Check if ::get throws exception with bad arguments
    $test_bad_args = [
      [null, null],
      ['test_type', null],
      [1, 1],
      ['test_type', 1],
      [[], []],
      ['test_type', []],
      [new \stdClass, new \stdClass],
      ['test_type', new \stdClass],
    ];

    foreach ($test_bad_args as $arg_set) {
      
      $I->expectException(Exception::class, function() use($tracker, $arg_set) {
        $tracker->get($arg_set[0], $arg_set[1]);
      });
    }
  }
}

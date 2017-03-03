<?php

use Ponticlaro\Bebop\Common\ObjectTracker;

class ObjectTrackerCest
{
  public function _before(UnitTester $I)
  {
  }

  public function _after(UnitTester $I)
  {
    \Mockery::close();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\ObjectTracker::getInstance
   * @covers Ponticlaro\Bebop\Common\ObjectTracker::track
   * @covers Ponticlaro\Bebop\Common\ObjectTracker::get
   * 
   * @param UnitTester $I Tester Module
   */
  public function trackGet(UnitTester $I)
  {
    // Mock TrackableObjectInterface
    $trackable_mock = \Mockery::mock('\Ponticlaro\Bebop\Common\Patterns\TrackableObjectInterface');
    $trackable_mock->shouldReceive('getObjectID')->once()->andReturn('test_id');
    $trackable_mock->shouldReceive('getObjectType')->once()->andReturn('test_type');

    // Track and get mocked TrackableObjectInterface instance
    $tracker   = ObjectTracker::getInstance();
    $trackable = $tracker->track($trackable_mock)->get('test_type', 'test_id');

    // Verify the returned object matches the tracker one
    $I->assertEquals($trackable_mock, $trackable);

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

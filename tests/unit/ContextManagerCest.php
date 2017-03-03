<?php

use AspectMock\Test;
use Ponticlaro\Bebop\Common\ContextContainer;
use Ponticlaro\Bebop\Common\ContextManager;

class ContextManagerCest
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
   * @covers Ponticlaro\Bebop\Common\ContextManager::__construct
   * @covers Ponticlaro\Bebop\Common\ContextManager::defineCurrent
   * @covers Ponticlaro\Bebop\Common\ContextManager::getCurrent
   * 
   * @param UnitTester $I Tester Module
   */
  public function createAndCheckIfContextIsHomePosts(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(true);

    // Mock add_action
    $add_action_mock = Test::func('Ponticlaro\Bebop\Common', 'add_action', true);

    // Mock ContextContainer
    $container_mock = Test::double('Ponticlaro\Bebop\Common\ContextContainer');

    // Mock ContextManager
    $m_mock = Test::double('Ponticlaro\Bebop\Common\ContextManager');

    // Create test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if ::__construct is called once
    $m_mock->verifyInvokedOnce('__construct');

    // Check if ::add is called once
    $m_mock->verifyInvokedOnce('add');

    // Check if ::defineCurrent is added to the wp hook
    $add_action_mock->verifyInvokedOnce([
      'wp',
      [
        $m, 
        'defineCurrent'
      ]
    ]);

    // Check if ContextContainer::run is called once
    $container_mock->verifyInvokedOnce('run');

    // Check if current environment matches 'home/posts'
    $I->assertEquals('home/posts', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsFrontPage(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false);
    $wp_query->shouldReceive('is_front_page')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('home/page', $m->getCurrent());

    $wp_query->shouldReceive('is_search')->andReturn(true);
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsSearch(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
            ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('search', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIs404(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('error/404', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsCategory(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('tax/category', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsTag(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('tax/tag', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsCustomTaxonony(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(true)
             ->shouldReceive('get')->with('taxonomy')->andReturn('custom-tax');

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('tax/custom-tax', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsPostTypeArchive(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(true)
             ->shouldReceive('get')->with('post_type')->andReturn('custom-type');

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('archive/custom-type', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsYearArchive(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(true)
             ->shouldReceive('is_year')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('archive/date/year', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsMonthArchive(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(true)
             ->shouldReceive('is_year')->andReturn(false)
             ->shouldReceive('is_month')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('archive/date/month', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsDayArchive(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(true)
             ->shouldReceive('is_year')->andReturn(false)
             ->shouldReceive('is_month')->andReturn(false)
             ->shouldReceive('is_day')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('archive/date/day', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsAuthorArchive(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(false)
             ->shouldReceive('is_author')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('archive/author', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsSinglePage(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(false)
             ->shouldReceive('is_author')->andReturn(false)
             ->shouldReceive('is_singular')->andReturn(true)
             ->shouldReceive('is_page')->andReturn(true);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('single/page', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsSingleCustomType(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(false)
             ->shouldReceive('is_author')->andReturn(false)
             ->shouldReceive('is_singular')->andReturn(true)
             ->shouldReceive('is_page')->andReturn(false)
             ->shouldReceive('get')->with('post_type')->andReturn('custom-type');

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('single/custom-type', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @depends createAndCheckIfContextIsHomePosts
   * 
   * @param UnitTester $I Tester Module
   */
  public function contextIsSinglePost(UnitTester $I)
  {
    // Mock WP_Query
    global $wp_query;

    $wp_query = Mockery::mock('\WP_Query');
    $wp_query->shouldReceive('is_home')->andReturn(false)
             ->shouldReceive('is_front_page')->andReturn(false)
             ->shouldReceive('is_search')->andReturn(false)
             ->shouldReceive('is_404')->andReturn(false)
             ->shouldReceive('is_category')->andReturn(false)
             ->shouldReceive('is_tag')->andReturn(false)
             ->shouldReceive('is_tax')->andReturn(false)
             ->shouldReceive('is_post_type_archive')->andReturn(false)
             ->shouldReceive('is_date')->andReturn(false)
             ->shouldReceive('is_author')->andReturn(false)
             ->shouldReceive('is_singular')->andReturn(true)
             ->shouldReceive('is_page')->andReturn(false)
             ->shouldReceive('get')->with('post_type')->andReturn(null);

    // Get test instance
    $m = ContextManager::getInstance()->defineCurrent();

    // Check if current environment matches
    $I->assertEquals('single/post', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::is
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function is(UnitTester $I)
  {
    // Get test instance
    $m = ContextManager::getInstance();

    // Check if current environment partially matches
    $I->assertTrue($m->is('single'));

    // Check if current environment partially matches, using a regular expression
    $I->assertTrue($m->is('/single\/po+/', true));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::equals
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function equals(UnitTester $I)
  {
    // Get test instance
    $m = ContextManager::getInstance();

    // Check if current environment partially matches
    $I->assertTrue($m->equals('single/post'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::overrideCurrent
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function overrideCurrent(UnitTester $I)
  {
    // Get test instance
    $m = ContextManager::getInstance()->overrideCurrent('testing/context');

    // Check if current environment matches
    $I->assertEquals('testing/context', $m->getCurrent());

    // Get test instance
    $m = ContextManager::getInstance()->overrideCurrent('testing/context_2');

    // Check if current environment matches
    $I->assertEquals('testing/context_2', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::restoreCurrent
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * @depends overrideCurrent
   * 
   * @param UnitTester $I Tester Module
   */
  public function restoreCurrent(UnitTester $I)
  {
    // Get test instance and restore context
    $m = ContextManager::getInstance()->restoreCurrent();

    // Check if current environment matches
    $I->assertEquals('testing/context', $m->getCurrent());

    // Restore context once more
    $m->restoreCurrent();

    // Check if current environment matches
    $I->assertEquals('single/post', $m->getCurrent());
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::get
   * @covers  Ponticlaro\Bebop\Common\ContextManager::__getContextById
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    // Get test instance
    $m = ContextManager::getInstance();

    $I->assertTrue($m->get('default') instanceof ContextContainer);
    $I->assertNull($m->get('invalid_container_id'));
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::add
   * @covers  Ponticlaro\Bebop\Common\ContextManager::prepend
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function add(UnitTester $I)
  {
    // Mocks
    $container_mock     = Test::double('Ponticlaro\Bebop\Common\ContextContainer');
    $m_mock             = Test::double('Ponticlaro\Bebop\Common\ContextManager');
    //$array_unshift_mock = Test::func('Ponticlaro\Bebop\Common', 'array_unshift', true);

    // Get test instance
    $m = ContextManager::getInstance();

    $m->add('prepended_container', 'context_container_callable');

    //$array_unshift_mock->verifyInvokedOnce();

    $m_mock->verifyInvokedOnce('prepend', [
      'prepended_container', 
      'context_container_callable'
    ]);

    $container_mock->verifyInvokedOnce('__construct', [
      'prepended_container', 
      'context_container_callable'
    ]);

    $I->assertTrue($m->get('prepended_container') instanceof ContextContainer);
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\ContextManager::append
   * @depends createAndCheckIfContextIsHomePosts
   * @depends contextIsSinglePost
   * 
   * @param UnitTester $I Tester Module
   */
  public function append(UnitTester $I)
  {   
    // Mock ContextContainer
    $container_mock = Test::double('Ponticlaro\Bebop\Common\ContextContainer');

    // Get test instance
    $m = ContextManager::getInstance();

    $m->append('appended_container', 'context_container_callable');

    $container_mock->verifyInvokedOnce('__construct', [
      'appended_container', 
      'context_container_callable'
    ]);

    $I->assertTrue($m->get('appended_container') instanceof ContextContainer);
  }
}

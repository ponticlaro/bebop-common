<?php

use Ponticlaro\Bebop\Common\UrlManager;

class UrlManagerCest
{
  /**
   * Mock values for default URLs
   * 
   * @var array
   */
  protected $urls = [
    'home'    => '/',
    'admin'   => '/wp-admin',
    'plugins' => '/wp-content/plugins',
    'content' => '/wp-content',
    'uploads' => '/wp-content/uploads',
    'themes'  => '/wp-content/themes',
    'theme'   => '/wp-content/themes/bebop',
  ];

  public function _before(UnitTester $I)
  {
    \WP_Mock::setUp();

    \WP_Mock::wpFunction('wp_upload_dir', [
      'return' => [
        'baseurl' => $this->urls['uploads']
      ]
    ]);

    \WP_Mock::wpFunction('get_bloginfo', [
      'args'   => 'template_url',
      'return' => $this->urls['theme']
    ]);

    \WP_Mock::wpFunction('home_url', [
      'return' => $this->urls['home']
    ]);

    \WP_Mock::wpFunction('admin_url', [
      'return' => $this->urls['admin']
    ]);
    \WP_Mock::wpFunction('plugins_url', [
      'return' => $this->urls['plugins']
    ]);

    \WP_Mock::wpFunction('content_url', [
      'return' => $this->urls['content']
    ]);
  }

  public function _after(UnitTester $I)
  {
    \WP_Mock::tearDown();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\UrlManager::getInstance
   * @covers Ponticlaro\Bebop\Common\UrlManager::__construct
   * @covers Ponticlaro\Bebop\Common\UrlManager::getAll
   * 
   * @param UnitTester $I Tester Module
   */
  public function checkDefaultUrls(UnitTester $I)
  {
    $urls = UrlManager::getInstance()->getAll();

    $I->assertEquals($this->urls, $urls);
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\UrlManager::set
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    $url = UrlManager::getInstance()->set('home', '/newhome')->get('home');

    $I->assertEquals('/newhome', $url);
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\UrlManager::has
   * @depends set
   * 
   * @param UnitTester $I Tester Module
   */
  public function has(UnitTester $I)
  {
    $m = UrlManager::getInstance();

    $I->assertTrue($m->has('home'));
    $I->assertTrue($m->has('admin'));
    $I->assertTrue($m->has('plugins'));
    $I->assertTrue($m->has('content'));
    $I->assertTrue($m->has('uploads'));
    $I->assertTrue($m->has('themes'));
    $I->assertTrue($m->has('theme'));

    // Test bad arguments
    $I->assertFalse($m->has(null));
    $I->assertFalse($m->has(1));
    $I->assertFalse($m->has([]));
    $I->assertFalse($m->has(new \stdClass));
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\UrlManager::get
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    $m = UrlManager::getInstance();
    
    // Testing value
    $url = $m->get('theme');

    $I->assertEquals($this->urls['theme'], $url);

    // Testing value + relative url
    $url = $m->get('theme', '/relative/url');

    $I->assertEquals($this->urls['theme'] .'/relative/url', $url);

    // Testing unexisting key
    $I->assertNull($m->get('not_set_url', '/relative/url'));

    // Testing bad arguments
    $I->assertNull($m->get(null, '/relative/url'));
    $I->assertNull($m->get(1, '/relative/url'));
    $I->assertNull($m->get([], '/relative/url'));
    $I->assertNull($m->get(new \stdClass, '/relative/url'));
  }
}
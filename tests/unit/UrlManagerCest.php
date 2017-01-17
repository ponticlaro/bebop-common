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
    'admin'   => '/wp-admin/',
    'plugins' => '/wp-content/plugins/',
    'content' => '/wp-content/',
    'uploads' => '/wp-content/uploads/',
    'themes'  => '/wp-content/themes/',
    'theme'   => '/wp-content/themes/bebop/',
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
   * Testing UrlManager::getAll() and default URLs
   * 
   * @param UnitTester $I Tester Module
   */
  public function checkDefaultUrls(UnitTester $I)
  {
    $urls = UrlManager::getInstance()->getAll();

    $I->assertEquals($this->urls, $urls);
  }

  /**
   * Testing UrlManager::set()
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    $url = UrlManager::getInstance()->set('home', '/newhome')->get('home');

    $I->assertEquals('/newhome', $url);
  }

  /**
   * Testing UrlManager::get()
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    // Testing value
    $url = UrlManager::getInstance()->get('theme');

    $I->assertEquals($this->urls['theme'], $url);

    // Testing value + relative url
    $url = UrlManager::getInstance()->get('theme', '/assets/main.css');

    $I->assertEquals($this->urls['theme'] .'assets/main.css', $url);
  }
}

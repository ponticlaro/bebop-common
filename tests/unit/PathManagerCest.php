<?php

use Ponticlaro\Bebop\Common\PathManager;

class PathManagerCest
{
  /**
   * Mock values for default paths
   * 
   * @var array
   */
  protected $paths = [
    'root'    => '/var/www/',
    'admin'   => '',
    'plugins' => '',
    'content' => '',
    'uploads' => '/wp-content/uploads/',
    'themes'  => '/wp-content/themes/',
    'theme'   => '/wp-content/themes/bebop/',
  ];

  public function _before(UnitTester $I)
  {
    if (!defined('ABSPATH'))
      define('ABSPATH', $this->paths['root']);

    \WP_Mock::setUp();

    \WP_Mock::wpFunction('wp_upload_dir', [
      'return' => [
        'basedir' => $this->paths['uploads']
      ]
    ]);

    \WP_Mock::wpFunction('get_template_directory', [
      'return' => $this->paths['theme']
    ]);
  }

  public function _after(UnitTester $I)
  {
    \WP_Mock::tearDown();
  }

  /**
   * Testing PathManager::getAll() and default URLs
   * 
   * @param UnitTester $I Tester Module
   */
  public function checkDefaultUrls(UnitTester $I)
  {
    $paths = PathManager::getInstance()->getAll();

    $I->assertEquals($this->paths, $paths);
  }

  /**
   * Testing PathManager::set()
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    $path = PathManager::getInstance()->set('root', '/var/www/html')->get('root');

    $I->assertEquals('/var/www/html', $path);
  }

  /**
   * Testing PathManager::get()
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    $path = PathManager::getInstance()->get('theme', '/assets/main.css');

    $I->assertEquals($this->paths['theme'] .'assets/main.css', $path);
  }
}

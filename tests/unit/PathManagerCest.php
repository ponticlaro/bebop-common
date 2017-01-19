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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\PathManager::__construct
   * @covers Ponticlaro\Bebop\Common\PathManager::getAll
   * 
   * @param UnitTester $I Tester Module
   */
  public function checkDefaultUrls(UnitTester $I)
  {
    $paths = PathManager::getInstance()->getAll();

    $I->assertEquals($this->paths, $paths);
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\PathManager::set
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    $path = PathManager::getInstance()->set('root', '/var/www/html')->get('root');

    $I->assertEquals('/var/www/html', $path);
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\PathManager::get
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    // Testing value
    $path = PathManager::getInstance()->get('theme');

    $I->assertEquals($this->paths['theme'], $path);

    // Testing value + relative path
    $path = PathManager::getInstance()->get('theme', '/assets/main.css');

    $I->assertEquals($this->paths['theme'] .'assets/main.css', $path);
  }
}

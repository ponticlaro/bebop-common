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
    'root'    => '/var/www',
    'admin'   => '',
    'plugins' => '',
    'content' => '',
    'uploads' => '/wp-content/uploads',
    'themes'  => '/wp-content/themes',
    'theme'   => '/wp-content/themes/bebop',
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
   * @covers Ponticlaro\Bebop\Common\PathManager::getInstance
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
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\PathManager::set
   * @depends checkDefaultUrls
   * 
   * @param UnitTester $I Tester Module
   */
  public function set(UnitTester $I)
  {
    $path = PathManager::getInstance()->set('root', '/var/www/html')->get('root');

    $I->assertEquals('/var/www/html', $path);
  }

  /**
   * @author  cristianobaptista
   * @covers  Ponticlaro\Bebop\Common\PathManager::has
   * @depends set
   * 
   * @param UnitTester $I Tester Module
   */
  public function has(UnitTester $I)
  {
    $m = PathManager::getInstance();

    $I->assertTrue($m->has('root'));
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
   * @covers Ponticlaro\Bebop\Common\PathManager::get
   * @depends has
   * 
   * @param UnitTester $I Tester Module
   */
  public function get(UnitTester $I)
  {
    $m = PathManager::getInstance();

    // Testing value
    $path = $m->get('theme');

    $I->assertEquals($this->paths['theme'], $path);

    // Testing value + relative path
    $path = $m->get('theme', '/relative/path');

    $I->assertEquals($this->paths['theme'] .'/relative/path', $path);

    // Testing unexisting key
    $I->assertNull($m->get('not_set_path', '/relative/path'));

    // Testing bad arguments
    $I->assertNull($m->get(null, '/relative/path'));
    $I->assertNull($m->get(1, '/relative/path'));
    $I->assertNull($m->get([], '/relative/path'));
    $I->assertNull($m->get(new \stdClass, '/relative/path'));
  }
}
<?php

use AspectMock\Test;
use Codeception\Util\Stub;
use Ponticlaro\Bebop\Common\Utils;

class UtilsCest
{
  public function _before(UnitTester $I)
  {
    Test::clean();
    \WP_Mock::setUp();
  }

  public function _after(UnitTester $I)
  {
    Test::clean();
    \WP_Mock::tearDown();
  }

  /**
   * Testing Utils::isNetwork()
   * 
   * @param UnitTester $I Tester Module
   */
  public function isNetwork(UnitTester $I)
  {
    \WP_Mock::wpFunction('is_multisite', [
      'times'           => 2,
      'return_in_order' => [
        true,
        false
      ]
    ]);

    $I->assertTrue(Utils::isNetwork());
    $I->assertFalse(Utils::isNetwork());
  }

  /**
   * Testing Utils::camelcaseToUnderscore()
   * 
   * @param UnitTester $I Tester Module
   */
  public function camelcaseToUnderscore(UnitTester $I)
  {
    $result_strings = [
      'camel_case_function_name',
      'a_camel_case_function_name',
      'another_camel_case_function_name',
      '__another_camel_case_function_name__',
      'a_camel_case_function_name',
    ];

    $src_strings = [
      'CamelCaseFunctionName',            // Starting with an uppercase letter
      'aCamelCaseFunctionName',           // Starting with a single lowercase letter
      'anotherCamelCaseFunctionName',     // Starting with multiple lowercase letters
      '__anotherCamelCaseFunctionName__', // Starting and ending with underscore
      'a_camel_case_function_name',       // Already "camelcased"
    ];

    foreach ($src_strings as $key => $value) {
      $src_strings[$key] = Utils::camelcaseToUnderscore($value);
    }

    $I->assertEquals($result_strings[0], $src_strings[0]);
    $I->assertEquals($result_strings[1], $src_strings[1]);
    $I->assertEquals($result_strings[2], $src_strings[2]);
    $I->assertEquals($result_strings[3], $src_strings[3]);
    $I->assertEquals($result_strings[4], $src_strings[4]);
  }

  /**
   * Testing Utils::slugify()
   * 
   * @param UnitTester $I Tester Module
   */
  public function slugify(UnitTester $I)
  {
    \WP_Mock::wpFunction('remove_accents', [
      'times'  => 2,
      'return' => 'Its a random article about papier-mache'
    ]);

    $result_strings = [
      'its_a_random_article_about_papier-mache',
      'its-a-random-article-about-papier-mache'
    ];

    $src_strings = [
      'It\'s a random article about papier-mâché',
      'It\'s a random article about papier-mâché'
    ];

    // Testing default separator
    $src_strings[0] = Utils::slugify($src_strings[0]);

    $I->assertEquals($result_strings[0], $src_strings[0]);

    // Testing custom separator
    $src_strings[1] = Utils::slugify($src_strings[1], ["separator" => "-"]);

    $I->assertEquals($result_strings[1], $src_strings[1]);
  }

  /**
   * Testing Utils::parseMarkdown()
   * 
   * @param UnitTester $I Tester Module
   */
  public function parseMarkdown(UnitTester $I)
  {
    $content = '<h1>Heading</h1>';

    // Mock ParsedownExtra::text()
    $parser = Test::double(new ParsedownExtra, [
      'text' => $content
    ]);

    // Mock Utils::fixPunctuation()
    $utils = Test::double('Ponticlaro\Bebop\Common\Utils', [
      'fixPunctuation' => $content
    ]);

    $parsed_content = Utils::parseMarkdown('# Heading', ["fix_punctuation" => true]);

    // Verify ParsedownExtra::text() got invoked
    $parser->verifyMethodInvoked('text');

    // Verify Utils::fixPunctuation() got invoked
    $utils->verifyInvoked('fixPunctuation');

    $I->assertEquals($content, $parsed_content);
  }

  /**
   * Testing Utils::fixPunctuation()
   * 
   * @param UnitTester $I Tester Module
   */
  public function fixPunctuation(UnitTester $I)
  {
    $I->assertEquals('Text', Utils::fixPunctuation('Text'));
  } 

  /**
   * Testing Utils::isJson()
   * 
   * @param UnitTester $I Tester Module
   */
  public function isJson(UnitTester $I)
  {
    // Test valid JSON samples
    $json_samples = [
      '{}',
      '[]',
      '{"key":"val"}',
      '["val_1","val_2"]',
    ];

    foreach ($json_samples as $json) {
      $I->assertTrue(Utils::isJson($json));
    }

    // Test invalid JSON samples
    $non_json_samples = [
      1,
      [1,2,3],
      new stdClass,
      'text',
      '["key":"value"]',
    ];

    foreach ($non_json_samples as $non_json) {
      $I->assertFalse(Utils::isJson($non_json));
    }
  } 

  /**
   * Testing Utils::getFileVersion()
   * 
   * @param UnitTester $I Tester Module
   */
  public function getFileVersion(UnitTester $I)
  {
    $file_time = 123456789;

    // Mock file_exists()
    $file_exists = Test::func('Ponticlaro\Bebop\Common', 'file_exists', true);

    // Mock filemtime()
    $filemtime = Test::func('Ponticlaro\Bebop\Common', 'filemtime', $file_time);

    $I->assertEquals($file_time, Utils::getFileVersion('/path/to/file.php'));

    // Verify file_exists() got invoked
    $file_exists->verifyInvokedOnce();

    // Verify filemtime() got invoked
    $filemtime->verifyInvokedOnce();
  } 

  /**
   * Testing Utils::getPathUrl()
   * 
   * @param UnitTester $I Tester Module
   */
  public function getPathUrl(UnitTester $I)
  {
    \WP_Mock::wpFunction('home_url', [
      'times'  => 1,
      'return' => 'http://wp.local'
    ]);

    $src_path             = '/var/www/wp-content/themes/bebop/assets/css/main.css';
    $result_absolute_path = 'http://wp.local/wp-content/themes/bebop/assets/css/main.css';
    $result_relative_path = '/wp-content/themes/bebop/assets/css/main.css';

    $I->assertEquals($result_absolute_path, Utils::getPathUrl($src_path));
    $I->assertEquals($result_relative_path, Utils::getPathUrl($src_path, true));
  } 

  /**
   * Testing Utils::getControlNamesFromCallable()
   * 
   * @param UnitTester $I Tester Module
   */
  public function getControlNamesFromCallable(UnitTester $I)
  {
    
  } 
}
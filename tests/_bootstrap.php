<?php

// Autoload
require_once __DIR__ .'/../vendor/autoload.php';

// WordPress constants
if (!defined('ABSPATH'))
  define('ABSPATH', '/var/www/');

if (!defined('WP_CONTENT_URL'))
  define('WP_CONTENT_URL', 'http://wp.local/wp-content');

// Bootstrap Aspect Mock
$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
  'debug'        => true,
  'includePaths' => [
    __DIR__.'/../src'
  ]
]);
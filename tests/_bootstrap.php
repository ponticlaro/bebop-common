<?php

// Autoload
require_once __DIR__ .'/../vendor/autoload.php';

// WordPress constants
define('ABSPATH', '/var/www/');
define('WP_CONTENT_URL', 'http://wp.local/wp-content');

// Bootstrap Aspect Mock
$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
  'debug'        => true,
  'includePaths' => [
    __DIR__.'/../src'
  ]
]);
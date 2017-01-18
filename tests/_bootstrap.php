<?php

// Autoload
require_once __DIR__ .'/../vendor/autoload.php';

// Bootstrap Aspect Mock
$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
  'debug'        => true,
  'includePaths' => [
    __DIR__.'/../src'
  ]
]);
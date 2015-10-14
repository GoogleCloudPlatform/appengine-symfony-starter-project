<?php

use AppEngine\Environment;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

Environment::doAppEngineCheck();

$kernel = new AppKernel();
$kernel->boot();

Environment::clearCache($kernel->getCacheDir());

echo 'cache cleared';
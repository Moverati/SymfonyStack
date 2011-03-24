<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */

/**
 * Bootstrap
 *
 * @author    Geoffrey Tran
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */

require_once __DIR__ . '/../app/bootstrap.php.cache';
require_once __DIR__ . '/../app/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;

// Environment
$environment = isset($_ENV['ENVIRONMENT'])
                ? $_ENV['ENVIRONMENT']
                : AppKernel::ENVIRONMENT_PROD;

// Whether to force debugging
$debug       = (isset($_ENV['DEBUG']))
                ? (bool) $_ENV['DEBUG'] : null;

// Wether to use the internal app cache
$httpCache   = (isset($_ENV['HTTP_CACHE']))
                ? (bool) $_ENV['HTTP_CACE'] : false;

// Setup the kernel and handle the request
$kernel = new AppKernel($environment, $debug);

// Http Caching
if ($httpCache) {
    require_once __DIR__ . '/../app/bootstrap_cache.php.cache';
    require_once __DIR__ . '/../app/AppCache.php';
    
    $kernel = new AppCache($kernel);
}

$kernel->handle(Request::createFromGlobals())
       ->send();
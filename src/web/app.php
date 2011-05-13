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
$environment = isset($_SERVER['ENVIRONMENT']) ? $_SERVER['ENVIRONMENT']
                                              : AppKernel::ENVIRONMENT_PROD;

// Whether to force debugging
$debug       = isset($_SERVER['DEBUG'])       ? (bool) $_SERVER['DEBUG']      : null;

// Wether to use the internal app cache
$httpCache   = isset($_SERVER['HTTP_CACHE'])  ? (bool) $_SERVER['HTTP_CACHE'] : false;

// Setup the kernel and handle the request
$kernel = new AppKernel($environment, $debug);

// Http Caching
if ($httpCache) {
    require_once __DIR__ . '/../app/bootstrap_cache.php.cache';
    require_once __DIR__ . '/../app/AppCache.php';

    $kernel = new AppCache($kernel);
}

// Handle the request
$kernel->handle(Request::createFromGlobals())
       ->send();
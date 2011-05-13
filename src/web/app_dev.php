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

if (!isset($_SERVER['ENVIRONMENT']) || $_SERVER['ENVIRONMENT'] != 'prod') {
    $_SERVER['ENVIRONMENT'] = 'dev';
    $_SERVER['DEBUG']       = (isset($_SERVER['DEBUG']) && !$_SERVER['DEBUG']) ? false : true;
}

require_once 'app.php';
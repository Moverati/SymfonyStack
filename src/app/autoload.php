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

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'  => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Sensio'   => __DIR__.'/../vendor/bundles',
    'JMS'      => __DIR__.'/../vendor/bundles',

    // Doctrine Libraries
    'Doctrine\\Common'                 => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\Common\\DataFixtures'   => __DIR__.'/../vendor/doctrine-data-fixtures/lib',
    'Doctrine\\DBAL'                   => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine\\DBAL\\Migrations'       => __DIR__.'/../vendor/doctrine-migrations/lib',
    'Doctrine'                         => __DIR__.'/../vendor/doctrine/lib',

    'Assetic'          => __DIR__.'/../vendor/assetic/src',
    'Monolog'          => __DIR__.'/../vendor/monolog/src',

    'Acme'             => __DIR__.'/../src',
    'Knplabs'          => __DIR__.'/../vendor/bundles',
    'FOS'              => __DIR__.'/../vendor/bundles',
    'Moverati'         => __DIR__.'/../src',
    'RAPP'         => __DIR__.'/../src',
));

$loader->registerPrefixes(array(
    'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
    'Swift_'           => __DIR__.'/../vendor/swiftmailer/lib/classes',
));

$loader->register();
$loader->registerPrefixFallback(array(
    __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs',
));

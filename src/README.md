Moverati Symfony Stack
----------------------

Requirements
----------------------

PHP 5.3.2+    [http://php.net/]
APC 3.1+      [http://pecl.php.net/package/APC]
XDebug 2.1+   [http://www.xdebug.org/]
XSL

Phing 2.4.2+            [http://phing.info/]
PHPUnit 3.6+            [http://www.phpunit.de/]
PHP_CodeSniffer-1.3.0+  [http://pear.php.net/package/PHP_CodeSniffer/]

Additional Requirements
-----------------------

Symfony2 CodeSniffer   [https://github.com/opensky/Symfony2-coding-standard]



Installation
------------

# PHPUnit
pear channel-discover pear.phpunit.de
pear channel-discover components.ez.no
pear channel-discover pear.symfony-project.com
pear install --alldeps phpunit/PHPUnit
pear install phpunit/phpcpd
pear install phpunit/phploc

# Code browser
pear install --alldeps phpunit/PHP_CodeBrowser

# Phing
pear channel-discover pear.phing.info
pear install phing/phing

# Docblox
pear install PHP_UML

# PHP Depend
pear channel-discover pear.pdepend.org
pear install pdepend/PHP_Depend

# PMD
pear channel-discover pear.phpmd.org
pear install phpmd/PHP_PMD

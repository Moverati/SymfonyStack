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

use Symfony\Component\HttpKernel\Kernel,
    Symfony\Component\Config\Loader\LoaderInterface,
    Symfony\Component\ClassLoader\DebugUniversalClassLoader,
    Symfony\Component\HttpKernel\Debug\ErrorHandler,
    Symfony\Component\HttpKernel\Debug\DebugExceptionHandler;

/**
 * Application Kernel
 *
 * @author    Geoffrey Tran
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class AppKernel extends Kernel
{
    const ENVIRONMENT_DEV  = 'dev';
    const ENVIRONMENT_PROD = 'prod';
    const ENVIORNMENT_TEST = 'test';


    /**
     * Environments that have debugging enabled
     *
     * @var array
     */
    private $debugEnvironments = array(
        self::ENVIRONMENT_DEV,
        self::ENVIORNMENT_TEST
    );

    /**
     * Construct
     *
     * @param string  $environment
     * @param boolean $debug
     */
    public function __construct($environment, $debug = null)
    {
        // Normalize environment string
        $environment = strtolower($environment);

        // Determine Environment
        $environment = $this->determineEnvironment($environment);

        // Determine Debug
        $debug       = $this->determineDebug($debug, $environment);

        if ($debug) {
            // Pretty exceptions
            set_exception_handler(new DebugExceptionHandler());
        }

        parent::__construct($environment, $debug);

        // Load the class cache
        $this->loadClassCache();
    }

    /**
     * Init
     *
     */
    public function init()
    {
        if ($this->debug) {
            ini_set('display_errors', true);
            error_reporting(-1);

            DebugUniversalClassLoader::enable();
            ErrorHandler::register();
        }
    }

    /**
     * Register Bundles
     *
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            // Core Symfony Bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\DoctrineMigrationsBundle\DoctrineMigrationsBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            new Acme\DemoBundle\AcmeDemoBundle(),
            new FOS\UserBundle\FOSUserBundle(),

            //new Knplabs\Bundle\PaginatorBundle\KnplabsPaginatorBundle(),
            new Moverati\Bundle\KnplabsPaginatorBundle\KnplabsPaginatorBundle(),
        );

        if (in_array($this->getEnvironment(), $this->debugEnvironments)) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
         //   $bundles[] = new Symfony\Bundle\WebConfiguratorBundle\SymfonyWebConfiguratorBundle();
        }

        return $bundles;
    }

    /**
     * Register container configuration
     *
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->environment . '.xml');
    }


    /**
     * Gets the cache directory.
     *
     * @return string The cache directory
     */
    public function getCacheDir()
    {
        return $this->getDataDir() . '/cache/' . $this->environment;
    }

    /**
     * Gets the log directory.
     *
     * @return string The log directory
     */
    public function getLogDir()
    {
        return $this->getDataDir() . '/logs';
    }

    /**
     * Gets the data directory
     *
     * @return string the data directory
     */
    public function getDataDir()
    {
        return $this->rootDir . '/../data';
    }

    /**
     * Returns the kernel parameters.
     *
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters()
    {
        $params = array_merge(parent::getKernelParameters(), array(
            'kernel.data_dir' => $this->getDataDir()
        ));

        return $params;
    }

    /**
     * Determine the environment
     *
     * @param string $environment
     * @return string
     */
    private function determineEnvironment($environment)
    {
        if (in_array($environment, $this->debugEnvironments)
            && isset($_SERVER['REQUEST_URI'])
            && preg_match('/^\/app_([a-zA-Z0-9]+)\.php/', $_SERVER['REQUEST_URI'], $environmentMatch)) {
            // Set url forced environment (/app_prod.php, /app_dev.php)
            $environment = $environmentMatch[1];

            // Fix $_SERVER vars
            $_SERVER['SCRIPT_NAME']     = str_replace('/' . basename($_SERVER['SCRIPT_NAME']),
                                                      $environmentMatch[0],
                                                      $_SERVER['SCRIPT_NAME']);

            $_SERVER['SCRIPT_FILENAME'] = str_replace('/' . basename($_SERVER['SCRIPT_FILENAME']),
                                                      $environmentMatch[0],
                                                      $_SERVER['SCRIPT_FILENAME']);

            $_SERVER['PHP_SELF']        = str_replace('/' . basename($_SERVER['PHP_SELF']),
                                                      $environmentMatch[0],
                                                      $_SERVER['PHP_SELF']);
        }

        return $environment;
    }


    /**
     * Determine the debug setting
     *
     * @param boolean $debug
     * @param string  $environment
     * @return boolean
     */
    private function determineDebug($debug, $environment)
    {
        // Enable debug for supported environments by default
        if ($debug === null && in_array($environment, $this->debugEnvironments)) {
            $debug = true;
        }

        return $debug;
    }
}
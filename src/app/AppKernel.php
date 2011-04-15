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
    Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Application Kernel
 *
 * @author    Geoffrey Tran
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class AppKernel extends Kernel
{
    const ENVIRONMENT_PROD = 'prod';
    const ENVIORNMENT_INT  = 'int';
    const ENVIRONMENT_DEV  = 'dev';
    
    /**
     * Environments that have debugging enabled
     *
     * @var array
     */
    private $debugEnvironments = array(
        self::ENVIRONMENT_DEV,
        self::ENVIORNMENT_INT
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

        // Enable debug for supported environments by default
        if ($debug === null && in_array($environment, $this->debugEnvironments)) {
            $debug = true;
        }
        
        parent::__construct($environment, $debug);
    }

    /**
     * Register Bundles
     *
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            //new Symfony\Bundle\DoctrineMigrationsBundle\DoctrineMigrationsBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Acme\DemoBundle\AcmeDemoBundle(),
            new Moverati\Bundle\KnplabsPaginatorBundle\KnplabsPaginatorBundle(),
            new FOS\UserBundle\FOSUserBundle()
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
}

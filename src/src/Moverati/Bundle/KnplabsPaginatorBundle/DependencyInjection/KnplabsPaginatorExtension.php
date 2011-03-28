<?php

namespace Moverati\Bundle\KnplabsPaginatorBundle\DependencyInjection;

use Knplabs\PaginatorBundle,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\Reference;

class KnplabsPaginatorExtension extends PaginatorBundle\DependencyInjection\KnplabsPaginatorExtension
{    
    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     *
     * @return string
     */
    public function getNamespace()
    {
        return false;
    }

}

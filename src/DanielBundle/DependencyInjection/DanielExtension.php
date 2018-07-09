<?php
namespace DanielBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class DashboardExtension
 *
 * @package ${NAMESPACE}
 */
class DanielExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('entities.yml');
        $loader->load('controllers.yml');
    //    $loader->load('services.yml');
//        $loader->load('forms.yml');
        $loader->load('managers.yml');
    }
}

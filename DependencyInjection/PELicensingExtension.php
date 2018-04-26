<?php

namespace PE\Bundle\LicensingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class PELicensingExtension extends Extension
{
    /**
     * @var array
     */
    private static $drivers = [
        'orm' => [
            'registry' => 'doctrine',
        ],
        'mongodb' => [
            'registry' => 'doctrine_mongodb',
        ],
    ];

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        if ('custom' !== $config['driver']) {
            if (!isset(self::$drivers[$config['driver']])) {
                throw new \RuntimeException('Unknown driver');
            }

            // Set registry alias
            $container->setAlias(
                'pe_licensing.doctrine_registry',
                new Alias(self::$drivers[$config['driver']]['registry'], false)
            );

            // Set factory to object manager
            $definition = $container->getDefinition('pe_licensing.object_manager');
            $definition->setFactory([new Reference('pe_licensing.doctrine_registry'), 'getManager']);

            // Set manager name to access in config
            $container->setParameter('pe_licensing.object_manager_name', $config['object_manager_name']);

            // Set parameter for switch mapping
            $container->setParameter('pe_licensing.backend_type.' . $config['driver'], true);

            // Set classes to use in default services
            $container->setParameter('pe_licensing.class.license', $config['class']['license']);
        }

        // Set aliases to services
        $container->setAlias('pe_licensing.repository.license', new Alias($config['service']['repository_license'], true));
        $container->setAlias('pe_licensing.manager', new Alias($config['service']['manager'], true));

        if (isset($config['client'])) {
            $definition = $container->getDefinition('pe_licensing.client.config');
            $definition->replaceArgument(0, $config['client']['id']);
            $definition->replaceArgument(1, $config['client']['server_key']);
            $definition->replaceArgument(2, $config['client']['server_uri']);
            $definition->replaceArgument(3, $config['client']['server_ttl']);
        }
    }
}
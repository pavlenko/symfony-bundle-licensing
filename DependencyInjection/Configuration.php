<?php

namespace PE\Bundle\LicensingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('pe_licensing');

        $drivers = ['orm', 'mongodb', 'custom'];

        $rootNode
            ->children()
                ->scalarNode('driver')
                    ->validate()
                        ->ifNotInArray($drivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of ' . json_encode($drivers))
                    ->end()
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('object_manager_name')->defaultNull()->end()
                ->arrayNode('class')
                    ->isRequired()
                    ->children()
                        ->scalarNode('license')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('manager')->defaultValue('pe_licensing.manager.default')->end()
                        ->scalarNode('repository_license')->defaultValue('pe_licensing.repository.license.default')->end()
                    ->end()
                ->end()
                ->arrayNode('server')
                    ->children()
                        ->scalarNode('server_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('License server encryption key')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('client')
                    ->children()
                        ->scalarNode('id')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('Unique client id')
                        ->end()
                        ->scalarNode('server_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('License server encryption key')
                        ->end()
                        ->scalarNode('server_uri')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('License server uri in format http(s)://example.com/check')
                        ->end()
                        ->scalarNode('server_ttl')
                            ->defaultValue(300)
                            ->info('Licence check result cache lifetime')
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->validate()
                ->ifTrue(function ($v) {
                    //TODO get back after complete component
                    return false;//isset($v['server'], $v['client']);
                })
                ->thenInvalid('You cannot use server and client at same time.')
            ->end()

            ->validate()
                ->ifTrue(function ($v) {
                    return 'custom' === $v['driver']
                        && (
                            'pe_licensing.repository.license.default' === $v['service']['repository_license']
                        );
                })
                ->thenInvalid('You need to specify your own services when using the "custom" driver.')
            ->end()
        ;

        return $treeBuilder;
    }
}
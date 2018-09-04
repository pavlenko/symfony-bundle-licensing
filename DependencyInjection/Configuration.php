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
                        ->scalarNode('client')->defaultValue('pe_licensing.client.default')->end()
                        ->scalarNode('server')->defaultValue('pe_licensing.server.default')->end()
                        ->arrayNode('repository')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('license')->defaultValue('pe_licensing.repository.license.default')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('server')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cache_ttl')->defaultValue(300)->end()
                    ->end()
                ->end()
            ->end()

            ->validate()
                ->ifTrue(function ($v) {
                    return 'custom' === $v['driver']
                        && (
                            'pe_licensing.repository.license.default' === $v['service']['repository']['license']
                        );
                })
                ->thenInvalid('You need to specify your own services when using the "custom" driver.')
            ->end()
        ;

        return $treeBuilder;
    }
}
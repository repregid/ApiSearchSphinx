<?php

namespace Repregid\ApiSearchSphinx\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Repregid\ApiSearchSphinx\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('repregid_api_search_sphinx');

        $rootNode
            ->children()
                ->scalarNode('indexPrefix')->defaultValue('')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

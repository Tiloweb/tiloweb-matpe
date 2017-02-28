<?php
/**
 * Created by PhpStorm.
 * User: tilotiti
 * Date: 20/02/2017
 * Time: 17:15
 */

namespace Tiloweb\MaTPEBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("ma_tpe");

        $rootNode
            ->children()
                ->scalarNode('login')->end()
                ->scalarNode('key')->end()
                ->scalarNode('firm')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
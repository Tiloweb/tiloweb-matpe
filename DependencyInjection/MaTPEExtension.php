<?php
/**
 * Created by PhpStorm.
 * User: tilotiti
 * Date: 20/02/2017
 * Time: 16:21
 */

namespace Tiloweb\MaTPEBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MaTPEExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load("services.yml");

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        foreach($config as $name => $node) {
            $container->setParameter('ma_tpe.'.$name, $node);
        }

        return $config;
    }
}
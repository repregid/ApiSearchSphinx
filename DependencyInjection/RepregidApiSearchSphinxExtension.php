<?php

namespace Repregid\ApiSearchSphinx\DependencyInjection;


use Repregid\ApiBundle\Service\Search\SearchEngineInterface;
use Repregid\ApiSearchSphinx\Sphinx\Sphinx;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class RepregidApiSearchSphinxExtension
 * @package Repregid\ApiSearchSphinx\DependencyInjection
 */
class RepregidApiSearchSphinxExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $sphinx = $container->getDefinition(Sphinx::class);
        $sphinx->addMethodCall('setPrefix', [$config['indexPrefix']]);
    }
}

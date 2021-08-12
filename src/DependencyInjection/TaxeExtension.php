<?php

namespace AcMarche\Taxe\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see https://symfony.com/doc/bundles/prepend_extension.html
 */
class TaxeExtension extends Extension implements PrependExtensionInterface
{
    private ?YamlFileLoader $loader = null;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $this->loader = $loader;

        $loader->load('services.yaml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container): void
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            foreach (array_keys($container->getExtensions()) as $name) {
                switch ($name) {
                    case 'doctrine':
                        $this->loadConfig($container, 'doctrine');
                        break;
                    case 'twig':
                        $this->loadConfig($container, 'twig');
                        break;
                    case 'framework':
                        $this->loadConfig($container, 'security');
                        break;
                    case 'vich_uploader':
                        $this->loadConfig($container, 'vich_uploader');
                        break;
                    case 'api_platform':
                        $this->loadConfig($container, 'api_platform');
                        break;
                }
            }
        }
    }

    protected function loadConfig(ContainerBuilder $container, string $name): void
    {
        $configs = $this->loadYamlFile($container);

        $configs->load($name . '.yaml');
        //  $container->prependExtensionConfig('doctrine', $configs);
    }

    /**
     * @param ContainerBuilder $container
     * @return Loader\YamlFileLoader
     */
    protected function loadYamlFile(ContainerBuilder $container): YamlFileLoader
    {
        return new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config/packages/')
        );
    }
}

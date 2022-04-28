<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Webinertia\ModelManager\ModelManager;

use function is_array;

class ModelManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return AbstractPluginManager
     */
    public function __invoke(ContainerInterface $container, $name, ?array $options = null)
    {
        $pluginManager = new ModelManager($container, $options ?: []);

        // If this is in a laminas-mvc application, the ServiceListener will inject
        // merged configuration during bootstrap.
        if ($container->has('ServiceListener')) {
            return $pluginManager;
        }

        // If we do not have a config service, nothing more to do
        if (! $container->has('config')) {
            return $pluginManager;
        }

        $config = $container->get('config');

        // If we do not have model_manager configuration, nothing more to do
        if (! isset($config['model_manager']) || ! is_array($config['model_manager'])) {
            return $pluginManager;
        }

        // Wire service configuration for models
        (new Config($config['model_manager']))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}

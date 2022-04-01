<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

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
        $pluginManager = new \Webinertia\ModelManager\ModelManager($container, $options ?: []);

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

    /**
     * {@inheritDoc}
     *
     * @return AbstractPluginManager
     */
    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this(
            $container,
            $requestedName ?: __NAMESPACE__ . '\ModelManager',
            $this->creationOptions
        );
    }
}

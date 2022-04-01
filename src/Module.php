<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\ModuleManager\ModuleManager;
use Webinertia\ModelManager\ConfigProvider;

class Module
{
    /**
     * Return webinertia-modulemanager configuration for laminas-mvc application.
     *
     * @return array
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }

    /**
     * Register a specification for the ModelManager with the ServiceListener.
     *
     * @param ModuleManager $moduleManager
     * @return void
     */
    public function init($moduleManager)
    {
        $event           = $moduleManager->getEvent();
        $container       = $event->getParam('ServiceManager');
        $serviceListener = $container->get('ServiceListener');

        $serviceListener->addServiceManager(
            'ModelManager',
            'model_manager',
            'Webinertia\ModuleManager\Feature\ModelProviderInterface',
            'getModelConfig'
        );
    }
}

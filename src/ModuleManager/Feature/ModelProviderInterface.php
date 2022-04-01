<?php

declare(strict_types=1);

namespace Webinertia\ModuleManager\Feature;

use Laminas\ServiceManager\Config;

interface ModelProviderInterface
{
    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getModelConfig();
}
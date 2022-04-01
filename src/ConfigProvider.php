<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration
     */
    public function getDependencyConfig(): array
    {
        return [
            'aliases'   => [
                ModelManager::class => 'ModelManager',
            ],
            'factories' => [
                'ModelManager' => ModelManagerFactory::class,
            ],
        ];
    }
}

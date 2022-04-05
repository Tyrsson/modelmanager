<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Webinertia\ModelManager\ModelInterface;

use function get_class;
use function gettype;
use function is_object;
use function sprintf;

/**
 * laminas-servicemanager v3-compatible plugin manager implementation for Models.
 *
 * Enforces that elements retrieved are instances of ModelInterface.
 */
class ModelManager extends AbstractPluginManager
{
    /**
     * Aliases for default set of helpers
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Factories for default set of helpers
     *
     * @var array
     */
    protected $factories = [];

    /**
     * Share Models by default (v3)
     *
     * @var bool
     */
    protected $sharedByDefault = true;

    /**
     * Interface all plugins managed by this class must implement.
     *
     * @var string
     */
    protected $instanceOf = ModelInterface::class;

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Validates against `$instanceOf`.
     *
     * @param  mixed $plugin
     * @throws InvalidServiceException
     * @return void
     */
    public function validate($plugin)
    {
        //parent::validate($plugin);
        if (! $plugin instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                get_class($this),
                $this->instanceOf,
                is_object($plugin) ? get_class($plugin) : gettype($plugin)
            ));
        }
    }
}

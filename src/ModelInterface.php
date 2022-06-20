<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\Permissions\Acl\ResourceInterface;

interface ModelInterface extends ResourceInterface
{
    public function getResourceId();
}

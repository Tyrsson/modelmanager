<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\Config\Config;
use Laminas\Db\ResultSet\Exception\InvalidArgumentException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\EventManager\EventManager;
use Laminas\Log\Logger;
use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use Laminas\Stdlib\ArrayObject;
use Webinertia\ModelManager\ModelInterface;
use Webinertia\ModelManager\ModelManager;
use Webinertia\ModelManager\TableGateway\TableGateway;

abstract class AbstractModel extends ArrayObject implements
    ResourceInterface,
    ProprietaryInterface,
    RoleInterface,
    ModelInterface
{
    /** @var Config $config */
    protected $config;
    /** @var TableGateway $db; */
    protected $db;
    /** @var Logger $logger */
    protected $logger;
    /**
     * String column name for the ownerId (usually points at the Users table id column)
     *
     * @var string $ownerIdColumn
     * */
    protected $ownerIdColumn;
    /**
     * @param string $table
     * @return void
     * @throws InvalidArgumentException
     * @throws ExceptionInvalidArgumentException
     */
    public function __construct($table, EventManager $eventManager, Config $config, ?Logger $logger = null)
    {
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($this);
        $this->db     = new TableGateway($table, $eventManager, $resultSetPrototype);
        $this->config = $config;
        parent::__construct([], ArrayObject::ARRAY_AS_PROPS);
    }

    public function getTablegateway(): TableGateway
    {
        return $this->db;
    }

    public function getRoleId(): string
    {
        if ($this->offsetExists('role')) {
            return $this->offsetGet('role');
        }
        return null;
    }

    public function getOwnerId(): int
    {
        if (! empty($this->ownerIdColumn) && $this->offsetExists($this->ownerIdColumn)) {
            return $this->offsetGet($this->ownerIdColumn);
        } elseif ($this->offsetExists('userId')) {
            return $this->offsetGet('userId');
        } elseif ($this->offsetExists('user_id')) {
            return $this->offsetGet('user_id');
        }
    }

    public function getResourceId(): string
    {
        return $this->db->getTable();
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    public function getSql(): Sql
    {
        return $this->db->getSql();
    }

    public function getResultSetPrototype(): ResultSet
    {
        return $this->db->getResultSetPrototype();
    }
}

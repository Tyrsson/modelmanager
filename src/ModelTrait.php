<?php

declare(strict_types=1);

namespace Webinertia\ModelManager;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Exception\InvalidArgumentException;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Exception\RuntimeException as TableGatewayRuntimeException;
use RuntimeException;
use Webinertia\ModelManager\ModelInterface;

use function sprintf;

trait ModelTrait
{
    /** @var AbstractTableGateway $db */
    protected $db;
    /** @var ResultSet $resultSet */
    protected $resultSet;
    /**
     * @param string $column
     * @param mixed $value
     * @return ModelInterface
     * @throws RuntimeException
     */
    public function fetchByColumn($column, $value)
    {
        $column = (string) $column;
        /** @var ResultSet $resultSet */
        $resultSet = $this->db->select([$column => $value]);
        $row       = $resultSet->current();
        if (! $row) {
            throw new RuntimeException(sprintf('Could not fetch column: ' . $column . ' with value: ' . $value));
        }
        return $row;
    }

    /**
     * @param string $column
     * @param mixed $value
     * @param null|array $columns
     * @return ModelInterface
     * @throws TableGatewayRuntimeException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function fetchColumns($column, $value, ?array $columns)
    {
        /** @var ResultSet $resultSet */
        $resultSet = $this->db->select(function (Select $select) use ($column, $value, $columns) {
            $select->columns($columns)->where([$column => $value]);
        });
        $row       = $resultSet->current();
        if (! $row) {
            throw new RuntimeException(
                sprintf('Could not fetch row with column: ' . $column . ' with value: ' . $value)
            );
        }
        return $row;
    }

    public function select(): ResultSetInterface
    {
        return $this->db->select();
    }

    public function fetchall(): ResultSetInterface
    {
        return $this->db->select();
    }

    public function insert(AbstractModel $model): int
    {
        $this->db->insert($model->toArray());
        return $this->db->getLastInsertValue();
    }

    /**
     * @param string|array|Where $where
     * @param null|array $joins
     * @throws TableGatewayRuntimeException
     * @throws InvalidArgumentException
     */
    public function update(AbstractModel $model, $where = null, ?array $joins = null): int
    {
        return $this->db->update($model->toArray(), $where, $joins);
    }

    public function getTable(): string
    {
        return $this->db->getTable();
    }

    public function getAdapter(): Adapter
    {
        return $this->db->getAdapter();
    }
}

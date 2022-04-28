<?php

declare(strict_types=1);

namespace Webinertia\ModelManager\TableGateway;

use Application\Model\AbstractModel;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Exception\RuntimeException;
use Laminas\Db\TableGateway\Feature\EventFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use Laminas\EventManager\EventManager;

class TableGateway extends AbstractTableGateway
{
    /**
     * Name of the database table
     *
     * @var string $table
     */
    public $table;
    /**
     * @param string $table (database table name)
     * @param AbstractModel $arrayObjectPrototype
     * @return void
     * @throws RuntimeException
     */
    public function __construct($table, EventManager $eventManager, ?ResultSet $resultSetPrototype = null)
    {
        // Set the table name
        $this->table = $table;
        // Create a FeatureSet
        $this->featureSet = new FeatureSet();
        $this->featureSet->setTableGateway($this);
        // Add the desired features
        $this->featureSet->addFeature(new GlobalAdapterFeature());
        // pass an instance of the EventManager
        $eventFeature = new EventFeature($eventManager);
        $this->featureSet->addFeature($eventFeature);
        $this->resultSetPrototype = $resultSetPrototype ?? new ResultSet();
        // inititalize this instance
        $this->initialize();
    }

    /**
     * @param ResultSet $resultSetPrototype
     */
    public function setResultSetPrototype($resultSetPrototype): void
    {
        $this->resultSetPrototype = $resultSetPrototype;
    }
}

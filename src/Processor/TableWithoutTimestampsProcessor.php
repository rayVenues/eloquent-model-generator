<?php

namespace Ray\EloquentModelGenerator\Processor;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;

class TableWithoutTimestampsProcessor implements ProcessorInterface
{
    public function __construct(private readonly DatabaseManager $databaseManager)
    {
    }

    /**
     * @throws Exception
     */
    public function process(EloquentModel $model, Config $config): void
    {
        if ($config->getNoTimestamps()) {
            return;
        }

        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
        $prefix = $this->databaseManager->connection($config->getConnection())->getTablePrefix();
        $tableDetails = $schemaManager->introspectTable($prefix . $model->getTableName());

        if (isset($tableDetails->getColumns()['created_at']) || isset($tableDetails->getColumns()['updated_at'])) {
            return;
        }

        $pNoTimestamps = new PropertyModel('timestamps', 'public', false);
        $pNoTimestamps->setDocBlock(
            docBlock: new DocBlockModel('Indicates if the model should be timestamped.', '', '@var bool')
        );
        $model->addProperty($pNoTimestamps);
    }

    public function getPriority(): int
    {
        return 6;
    }
}

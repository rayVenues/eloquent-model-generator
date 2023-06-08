<?php

namespace Ray\EloquentModelGenerator\Processor;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\Model\UseTraitModel;

class SoftDeleteTraitProcessor implements ProcessorInterface
{
    public function __construct(private readonly DatabaseManager $databaseManager)
    {
    }

    /**
     * @throws Exception
     */
    public function process(EloquentModel $model, Config $config): void
    {
        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
        $prefix = $this->databaseManager->connection($config->getConnection())->getTablePrefix();
        $tableDetails = $schemaManager->introspectTable($prefix . $model->getTableName());

        if (! isset($tableDetails->getColumns()['deleted_at'])) {
            return;
        }

        $softDeleteTrait = new UseTraitModel('\Illuminate\Database\Eloquent\SoftDeletes');
        $model->addTrait($softDeleteTrait);
    }

    public function getPriority(): int
    {
        return 6;
    }
}

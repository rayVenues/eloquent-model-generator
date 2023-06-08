<?php

namespace Ray\EloquentModelGenerator\Processor;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;
use Ray\EloquentModelGenerator\Model\VirtualPropertyModel;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\TypeRegistry;

class FieldProcessor implements ProcessorInterface
{
    public function __construct(private readonly DatabaseManager $databaseManager, private readonly TypeRegistry $typeRegistry)
    {
    }

    /**
     * @throws Exception
     */
    public function process(EloquentModel $model, Config $config): void
    {
        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();

        $tableDetails = $schemaManager->introspectTable(Prefix::add($model->getTableName()));
        $primaryColumnNames = $tableDetails->getPrimaryKey() ? $tableDetails->getPrimaryKey()->getColumns() : [];

        $columnNames = [];
        foreach ($tableDetails->getColumns() as $column) {
            $model->addProperty(new VirtualPropertyModel(
                $column->getName(),
                $this->typeRegistry->resolveType($column->getType()->getName())
            ));

            if (! in_array($column->getName(), $primaryColumnNames)) {
                $columnNames[] = $column->getName();
            }
        }

        $fillableProperty = new PropertyModel('fillable');
        $fillableProperty->setAccess('protected')
            ->setValue($columnNames)
            ->setDocBlock(new DocBlockModel('@var array'));
        $model->addProperty($fillableProperty);
    }

    public function getPriority(): int
    {
        return 5;
    }
}

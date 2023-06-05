<?php

namespace Ray\EloquentModelGenerator\Processor;

use Ray\EloquentModelGenerator\Processor\ProcessorInterface;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\TypeRegistry;

class CustomPrimaryKeyProcessor implements ProcessorInterface
{
    public function __construct(private DatabaseManager $databaseManager, private TypeRegistry $typeRegistry) {}

    public function process(EloquentModel $model, Config $config): void
    {
        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();

        $tableDetails = $schemaManager->introspectTable(Prefix::add($model->getTableName()));
        $primaryKey = $tableDetails->getPrimaryKey();
        if ($primaryKey === null) {
            return;
        }

        $columns = $primaryKey->getColumns();
        if (count($columns) !== 1) {
            return;
        }

        $column = $tableDetails->getColumn($columns[0]);
        if ($column->getName() !== 'id') {
            $primaryKeyProperty = new PropertyModel('primaryKey', 'protected', $column->getName());
//            $primaryKeyProperty->setDocBlock(
//                new DocBlockModel('The primary key for the model.', '', '@var string')
//            );
            $model->addProperty($primaryKeyProperty);
        }

        if ($column->getType()->getName() !== 'integer') {
            $keyTypeProperty = new PropertyModel(
                'keyType',
                'protected',
                $this->typeRegistry->resolveType($column->getType()->getName())
            );
//            $keyTypeProperty->setDocBlock(
//                new DocBlockModel('The "type" of the auto-incrementing ID.', '', '@var string')
//            );
            $model->addProperty($keyTypeProperty);
        }

        if (!$column->getAutoincrement()) {
            $autoincrementProperty = new PropertyModel('incrementing', 'public', false);
//            $autoincrementProperty->setDocBlock(
//                new DocBlockModel('Indicates if the IDs are auto-incrementing.', '', '@var bool')
//            );
            $model->addProperty($autoincrementProperty);
        }
    }

    public function getPriority(): int
    {
        return 6;
    }
}

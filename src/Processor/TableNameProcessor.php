<?php

namespace Ray\EloquentModelGenerator\Processor;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Model\ClassNameModel;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;
use Ray\EloquentModelGenerator\Model\UseClassModel;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Ray\EloquentModelGenerator\Model\EloquentModel;

class TableNameProcessor implements ProcessorInterface
{
    public function __construct(private readonly DatabaseManager $databaseManager)
    {
    }

    /**
     * @throws GeneratorException
     * @throws Exception
     */
    public function process(EloquentModel $model, Config $config): void
    {
        $className = $config->getClassName();
        $baseClassName = $config->getBaseClassName();
        $tableName = $config->getTableName() ?: EmgHelper::getTableNameByClassName($className);
        $classType = $config->getClassType();

        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
        $prefixedTableName = Prefix::add($tableName);
        if (! $schemaManager->tablesExist($prefixedTableName)) {
            throw new GeneratorException(sprintf('Table %s does not exist', $prefixedTableName));
        }

        $model
            ->setName(new ClassNameModel($className, EmgHelper::getShortClassName($baseClassName), $classType))
            ->addUses(new UseClassModel(ltrim($baseClassName, '\\')))
            ->setTableName($tableName);

        if ($model->getTableName() !== EmgHelper::getTableNameByClassName($className)) {
            $property = new PropertyModel('table', 'protected', $model->getTableName());
            $property->setDocBlock(new DocBlockModel('The table associated with the model.', '', '@var string'));
            $model->addProperty($property);
        }
    }

    public function getPriority(): int
    {
        return 10;
    }
}

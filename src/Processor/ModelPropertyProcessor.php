<?php

namespace Ray\EloquentModelGenerator\Processor;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;

class ModelPropertyProcessor implements ProcessorInterface
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
        /**
         * TODO:
         * - Add support for $config->getIncrementing()
         * - Add support for $config->getKeyName()
         * - Add support for $config->getHidden()
         * - Add support for $config->getVisible()
         * - Add support for $config->getFillable()
         * - Add support for $config->getGuarded()
         * - Add support for $config->getDates()
         * - Add support for $config->getCasts()
         * - Add support for $config->getAppends()
         * - Add support for $config->getKeyType()
         * - Add support for $config->getWith()
         * - Add support for $config->getAppends()
         */

        $tableName = $config->getTableName();
        if (! $tableName) {
            $model->setTableName(EmgHelper::getTableNameByClassName($config->getClassName()));
        } else {
            $model->setTableName($tableName);
            $className = EmgHelper::getTableNameByClassName($config->getClassName());
            $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
            if (! $schemaManager->tablesExist($tableName)) {
                throw new GeneratorException(sprintf('Table %s does not exist.', $tableName));
            }
            if ($tableName !== $className) {
                $pTableName = new PropertyModel('table', 'protected', $config->getTableName());
                $pTableName->setDocBlock(
                    docBlock: new DocBlockModel('The table associated with the model.', '@var string')
                );
                $model->addProperty($pTableName);
            }
        }


        if ($config->getPerPage() !== null) {
            $pPerPage = new PropertyModel('perPage', 'protected', $config->getPerPage());
            $pPerPage->setDocBlock(
                docBlock: new DocBlockModel('The number of models to return for pagination.', '@var int')
            );
            $model->addProperty($pPerPage);
        }

        if ($config->getDateFormat() !== null) {
            $pDateFormat = new PropertyModel('dateFormat', 'protected', $config->getDateFormat());
            $pDateFormat->setDocBlock(
                docBlock: new DocBlockModel('The storage format of the model\'s date columns.', '@var string')
            );
            $model->addProperty($pDateFormat);
        }

        if ($config->getConnection()) {
            $pConnection = new PropertyModel('connection', 'protected', $config->getConnection());
            $pConnection->setDocBlock(
                docBlock: new DocBlockModel('The connection name for the model.', '@var string')
            );
            $model->addProperty($pConnection);
        }
    }

    public function getPriority(): int
    {
        return 10;
    }
}

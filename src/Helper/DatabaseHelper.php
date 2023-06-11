<?php

namespace Ray\EloquentModelGenerator\Helper;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Model\EloquentModel;

class DatabaseHelper
{
    private static $instance;
    private static DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        self::$databaseManager = $databaseManager;
    }

    public static function getInstance(): DatabaseHelper
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(self::$databaseManager);
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public static function tableExists(Config $config): bool
    {
        $tableName = $config->getTableName();

        $schemaManager = self::$databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
        $prefixedTableName = Prefix::add($tableName);
        return ! $schemaManager->tablesExist($prefixedTableName);

    }

    /**
     * @return DatabaseManager
     */
    public static function getDatabaseManager(): DatabaseManager
    {
        return self::$databaseManager;
    }

    /**
     * @param DatabaseManager $databaseManager
     */
    public static function setDatabaseManager(DatabaseManager $databaseManager): void
    {
        self::$databaseManager = $databaseManager;
    }

}
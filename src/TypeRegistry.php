<?php

namespace Ray\EloquentModelGenerator;

use Doctrine\DBAL\Exception;
use Illuminate\Database\DatabaseManager;

class TypeRegistry
{
    protected array $types = [
        'array'        => 'array',
        'simple_array' => 'array',
        'json_array'   => 'string',
        'bigint'       => 'integer',
        'boolean'      => 'boolean',
        'datetime'     => 'string',
        'datetimetz'   => 'string',
        'date'         => 'string',
        'time'         => 'string',
        'decimal'      => 'float',
        'integer'      => 'integer',
        'object'       => 'object',
        'smallint'     => 'integer',
        'string'       => 'string',
        'text'         => 'string',
        'binary'       => 'string',
        'blob'         => 'string',
        'float'        => 'float',
        'guid'         => 'string',
        'enum'         => 'string',
    ];

    /**
     * @throws Exception
     */
    public function __construct(private readonly DatabaseManager $databaseManager)
    {
        foreach ($this->types as $sqlType => $phpType) {
            $this->registerDoctrineTypeMapping($sqlType, $phpType);
        }
    }

    /**
     * @throws Exception
     */
    public function registerType(string $sqlType, string $phpType, string $connection = null): void
    {
        $this->types[$sqlType] = $phpType;

        $this->registerDoctrineTypeMapping($sqlType, $phpType, $connection);
    }

    public function resolveType(string $type): string
    {
        return array_key_exists($type, $this->types) ? $this->types[$type] : 'mixed';
    }

    /**
     * @throws Exception
     */
    private function registerDoctrineTypeMapping(string $sqlType, string $phpType, string $connection = null): void
    {
        $dbConnection = $this->databaseManager->connection($connection)->getDoctrineConnection();
        $dbPlatform = $dbConnection->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping($sqlType, $phpType);
    }
}

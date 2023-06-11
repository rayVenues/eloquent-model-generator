<?php

use Illuminate\Database\Connectors\SQLiteConnector;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\SQLiteConnection;
use PHPUnit\Framework\MockObject\Exception;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Processor\ClassDefinitionProcessor;
use Ray\EloquentModelGenerator\Processor\CustomPrimaryKeyProcessor;
use Ray\EloquentModelGenerator\Processor\ModelPropertyProcessor;
use Ray\EloquentModelGenerator\Processor\FieldProcessor;
use Ray\EloquentModelGenerator\Processor\NamespaceProcessor;
use Ray\EloquentModelGenerator\Processor\RelationProcessor;
use Ray\EloquentModelGenerator\Processor\SoftDeleteProcessor;
use Ray\EloquentModelGenerator\Processor\TableTimestampsProcessor;
use Ray\EloquentModelGenerator\TypeRegistry;

// TODO: Implement beforeAll()

beforeEach(
/**
 * @throws Exception
 * @throws \Doctrine\DBAL\Exception
 */
    function () {
        $connector = new SQLiteConnector();
        $pdo = $connector->connect([
            'database' => ':memory:',
            'foreign_key_constraints' => true,
        ]);
        $this->connection = new SQLiteConnection($pdo);

        $queries = explode("\n\n", file_get_contents(__DIR__ . '/resources/schema.sql'));
        foreach ($queries as $query) {
            $this->connection->statement($query);
        }

        $databaseManagerMock = $this->createMock(DatabaseManager::class);
        $databaseManagerMock->expects($this->any())
            ->method('connection')
            ->willReturn($this->connection);

        $typeRegistry = new TypeRegistry($databaseManagerMock);

        $this->generator = new Generator([
            new ClassDefinitionProcessor(),
            new CustomPrimaryKeyProcessor($databaseManagerMock, $typeRegistry),
            new ModelPropertyProcessor($databaseManagerMock),
            new FieldProcessor($databaseManagerMock, $typeRegistry),
            new NamespaceProcessor(),
            new RelationProcessor($databaseManagerMock),
            new SoftDeleteProcessor($databaseManagerMock),
            new TableTimestampsProcessor($databaseManagerMock),
        ]);
    });

it('Generates a Model with a different table name.',
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setTableName('roles')
            ->setNamespace('App\Models')
            ->setBaseClassName(Model::class);

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        $b = file_get_contents(__DIR__ . '/resources/User-with-different-table-name.php.generated');
        expect($a)->toEqual($b);
    });

it('Generates a simple User Model.',
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setNamespace('App\Models')
            ->setBaseClassName(Model::class);

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        $b = file_get_contents(__DIR__ . '/resources/' . 'User' . '.php.generated');
        expect($a)->toEqual($b);
    });

it('Generates an abstract User Model.',
    /**
     * @throws \Exception
     */
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setNamespace('App\Models')
            ->setClassType('abstract')
            ->setBaseClassName(Model::class);

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        $b = file_get_contents(__DIR__ . '/resources/User-with-abstract-class.php.generated');
        expect($a)->toEqual($b);
    });

it('Generates a final User Model.',
    /**
     * @throws \Exception
     */
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setNamespace('App\Models')
            ->setClassType('final')
            ->setBaseClassName(Model::class);

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        $b = file_get_contents(__DIR__ . '/resources/User-with-final-class.php.generated');
        expect($a)->toEqual($b);
    });

it('Does not allow a class type other than `abstract` or `final`.',
    /**
     * @throws \Exception
     */
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setNamespace('App\Models')
            ->setClassType('not-allowed')
            ->setBaseClassName(Model::class);

        $this->generator->generateModel($config);
    })->throws('InvalidArgumentException');

it('Generates a model with custom properties.',
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setNamespace('App')
            ->setBaseClassName(Model::class)
            ->setDateFormat('d/m/y');

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        $b = file_get_contents(__DIR__ . '/resources/User-with-custom-properties.php.generated');
        expect($a)->toEqual($b);
    });

it('Generates a Model with output path and no namespace.',
    /**
     * @throws \Exception
     */
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setOutputPath('TempModels')
            ->setBaseClassName('Base\ClassName');

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        expect($a)->toContain('namespace App\TempModels');
    });

it('Generates a Model specifying output-path and namespace options. The namespace should be App\Models.',
    function () {
        $config = (new Config())
            ->setClassName('User')
            ->setOutputPath('TempModels')
            ->setNamespace('App\Models')
            ->setBaseClassName('Base\ClassName');

        $model = $this->generator->generateModel($config);
        $a = $model->render();
        expect($a)->toContain('namespace App\Models');
    });

//it('Disables Model timestamps when a table does not have created_at and updated_at columns.',
//    function () {
//        $config = (new Config())
//            ->setClassName('Role')
//            ->setNamespace('App\Models')
//            ->setBaseClassName(Model::class);
//
//        $model = $this->generator->generateModel($config);
//        $a = $model->render();
//        $b = file_get_contents(__DIR__ . '/resources/Role-without-created-at-and-updated-at.php.generated');
//        expect($a)->toEqual($b);
//    });



<?php

use Illuminate\Database\Connectors\SQLiteConnector;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\SQLiteConnection;
use PHPUnit\Framework\MockObject\Exception;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Processor\CustomPrimaryKeyProcessor;
use Ray\EloquentModelGenerator\Processor\CustomPropertyProcessor;
use Ray\EloquentModelGenerator\Processor\FieldProcessor;
use Ray\EloquentModelGenerator\Processor\NamespaceProcessor;
use Ray\EloquentModelGenerator\Processor\RelationProcessor;
use Ray\EloquentModelGenerator\Processor\TableNameProcessor;
use Ray\EloquentModelGenerator\TypeRegistry;

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
            new CustomPrimaryKeyProcessor($databaseManagerMock, $typeRegistry),
            new CustomPropertyProcessor(),
            new FieldProcessor($databaseManagerMock, $typeRegistry),
            new NamespaceProcessor(),
            new RelationProcessor($databaseManagerMock),
            new TableNameProcessor($databaseManagerMock),
        ]);

    });

it('Generates a User Model.',
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

it('Generates a model with custom properties.', function () {
    $config = (new Config())
        ->setClassName('User')
        ->setNamespace('App')
        ->setBaseClassName('Base\ClassName')
        ->setNoTimestamps()
        ->setDateFormat('d/m/y');

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/User-with-params.php.generated');
    expect($a)->toEqual($b);
});

it('Generates a model with a custom Class name.', function () {
    $config = (new Config())
        ->setClassName('UserModel')
        ->setNamespace('App')
        ->setBaseClassName('Base\ClassName')
        ->setNoTimestamps()
        ->setDateFormat('d/m/y');

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/User-with-custom-classname.php.generated');
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
        $b = file_get_contents(__DIR__ . '/resources/User-with-valid-out-path-without-namespace.php.generated');
        expect($a)->toEqual($b);
    });


<?php

use Illuminate\Database\Connectors\SQLiteConnector;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\SQLiteConnection;

use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Processor\CustomPrimaryKeyProcessor;
use Ray\EloquentModelGenerator\Processor\CustomPropertyProcessor;
use Ray\EloquentModelGenerator\Processor\FieldProcessor;
use Ray\EloquentModelGenerator\Processor\NamespaceProcessor;
use Ray\EloquentModelGenerator\Processor\RelationProcessor;
use Ray\EloquentModelGenerator\Processor\TableNameProcessor;
use Ray\EloquentModelGenerator\TypeRegistry;

beforeAll(static function () {
    $connector = new SQLiteConnector();
    $pdo = $connector->connect([
        'database' => ':memory:',
        'foreign_key_constraints' => true,
    ]);
    self::$connection = new SQLiteConnection($pdo);

    $queries = explode("\n\n", file_get_contents(__DIR__ . '/resources/schema.sql'));
    foreach ($queries as $query) {
        self::$connection->statement($query);
    }
});

beforeEach(function () {
    $databaseManagerMock = $this->createMock(DatabaseManager::class);
    $databaseManagerMock->expects($this->any())
        ->method('connection')
        ->willReturn(self::$connection);

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


test('generated abstract model', function () {
    $config = (new Config())
        ->setClassName('User')
        ->setNamespace('App\Models')
        ->setClassType('abstract')
        ->setBaseClassName(Model::class);

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/Abstract' . 'User' . '.php.generated');
    expect($b)->toEqual($a);
});

test('generated model', function () {
    $config = (new Config())
        ->setClassName('User')
        ->setNamespace('App\Models')
        ->setBaseClassName(Model::class);

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/' . 'User' . '.php.generated');
    expect($b)->toEqual($a);
});

test('generated model with custom properties', function () {
    $config = (new Config())
        ->setClassName('User')
        ->setNamespace('App')
        ->setBaseClassName('Base\ClassName')
        ->setNoTimestamps()
        ->setDateFormat('d/m/y');

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/User-with-params.php.generated');
    expect($b)->toEqual($a);
});

test('generated model custom class name', function () {
    $config = (new Config())
        ->setClassName('UserModel')
        ->setNamespace('App')
        ->setBaseClassName('Base\ClassName')
        ->setNoTimestamps()
        ->setDateFormat('d/m/y');

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/User-with-custom-classname.php.generated');
    expect($b)->toEqual($a);
});

/**
 * @throws Exception
 */
test('generated model output path without namespace is valid', function () {
    $config = (new Config())
        ->setClassName('User')
        ->setOutputPath('TempModels')
        ->setBaseClassName('Base\ClassName');

    $model = $this->generator->generateModel($config);
    $a = $model->render();
    $b = file_get_contents(__DIR__ . '/resources/User-with-valid-out-path-without-namespace.php.generated');
    expect($b)->toEqual($a);
});

// Helpers
/**
     * @dataProvider modelNameProvider
     */
function modelNameProvider(): array
{
    return [
        [
            'modelName' => 'User',
        ],
        [
            'modelName' => 'Role',
        ],
        [
            'modelName' => 'Organization',
        ],
        [
            'modelName' => 'Avatar',
        ],
        [
            'modelName' => 'Post',
        ],
    ];
}

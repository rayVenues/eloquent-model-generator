<?php

use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Database\Eloquent\Model;
use Ray\EloquentModelGenerator\Helper\EmgHelper;


test('get short class name', function (string $fqcn, string $expected) {
    expect(EmgHelper::getShortClassName($fqcn))->toEqual($expected);
})->with('fqcnProvider');

test('get table name by class name', function (string $className, string $expected) {
    expect(EmgHelper::getTableNameByClassName($className))->toEqual($expected);
})->with('classNameProvider');

test('get class name by table name', function (string $tableName, string $expected) {
    expect(EmgHelper::getClassNameByTableName($tableName))->toEqual($expected);
})->with('tableNameToClassNameProvider');

test('get default foreign column name', function (string $tableName, string $expected) {
    expect(EmgHelper::getDefaultForeignColumnName($tableName))->toEqual($expected);
})->with('tableNameToForeignColumnNameProvider');

test('get default join table name', function (string $tableNameOne, string $tableNameTwo, string $expected) {
    expect(EmgHelper::getDefaultJoinTableName($tableNameOne, $tableNameTwo))->toEqual($expected);
})->with('tableNamesProvider');

test('is column unique', function () {
    $indexMock = $this->createMock(Index::class);
    $indexMock->expects($this->once())
        ->method('getColumns')
        ->willReturn(['column_0']);

    $indexMock->expects($this->once())
        ->method('isUnique')
        ->willReturn(true);

    $indexMocks = [$indexMock];

    $tableMock = $this->createMock(Table::class);
    $tableMock->expects($this->once())
        ->method('getIndexes')
        ->willReturn($indexMocks);

    expect(EmgHelper::isColumnUnique($tableMock, 'column_0'))->toBeTrue();
});

test('is column unique two index columns', function () {
    $indexMock = $this->createMock(Index::class);
    $indexMock->expects($this->once())
        ->method('getColumns')
        ->willReturn(['column_0', 'column_1']);

    $indexMock->expects($this->never())
        ->method('isUnique');

    $indexMocks = [$indexMock];

    $tableMock = $this->createMock(Table::class);
    $tableMock->expects($this->once())
        ->method('getIndexes')
        ->willReturn($indexMocks);

    expect(EmgHelper::isColumnUnique($tableMock, 'column_0'))->toBeFalse();
});

test('is column unique index not unique', function () {
    $indexMock = $this->createMock(Index::class);
    $indexMock->expects($this->once())
        ->method('getColumns')
        ->willReturn(['column_0']);

    $indexMock->expects($this->once())
        ->method('isUnique')
        ->willReturn(false);

    $indexMocks = [$indexMock];

    $tableMock = $this->createMock(Table::class);
    $tableMock->expects($this->once())
        ->method('getIndexes')
        ->willReturn($indexMocks);

    expect(EmgHelper::isColumnUnique($tableMock, 'column_0'))->toBeFalse();
});

// Datasets
dataset('fqcnProvider', [
    ['fqcn' => Model::class, 'expected' => 'Model'],
    ['fqcn' => 'Custom\Name', 'expected' => 'Name'],
    ['fqcn' => 'ShortName', 'expected' => 'ShortName'],
]);

dataset('classNameProvider', [
    ['className' => 'User', 'expected' => 'users'],
    ['className' => 'ServiceAccount', 'expected' => 'service_accounts'],
    ['className' => 'Mouse', 'expected' => 'mice'],
    ['className' => 'D', 'expected' => 'ds'],
]);

dataset('tableNameToClassNameProvider', [
    ['className' => 'users', 'expected' => 'User'],
    ['className' => 'service_accounts', 'expected' => 'ServiceAccount'],
    ['className' => 'mice', 'expected' => 'Mouse'],
    ['className' => 'ds', 'expected' => 'D'],
]);

dataset('tableNameToForeignColumnNameProvider', [
    ['tableName' => 'organizations', 'expected' => 'organization_id'],
    ['tableName' => 'service_accounts', 'expected' => 'service_account_id'],
    ['tableName' => 'mice', 'expected' => 'mouse_id'],
]);

dataset('tableNamesProvider', [
    ['tableNameOne' => 'users', 'tableNameTwo' => 'roles', 'expected' => 'role_user'],
    ['tableNameOne' => 'roles', 'tableNameTwo' => 'users', 'expected' => 'role_user'],
    ['tableNameOne' => 'accounts', 'tableNameTwo' => 'profiles', 'expected' => 'account_profile'],
]);

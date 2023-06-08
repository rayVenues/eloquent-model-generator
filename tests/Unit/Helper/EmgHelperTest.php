<?php

use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Ray\EloquentModelGenerator\Helper\EmgHelper;


test('Get short class name', function (string $fqcn, string $expected) {
    $this->assertEquals($expected, EmgHelper::getShortClassName($fqcn));
})->with('fqcnProvider');

test('Get table name by class name', function (string $className, string $expected) {
    $this->assertEquals($expected, EmgHelper::getTableNameByClassName($className));
})->with('classNameProvider');

test('Get class name by table name', function (string $tableName, string $expected) {
    $this->assertEquals($expected, EmgHelper::getClassNameByTableName($tableName));
})->with('tableNameToClassNameProvider');

test('Get default foreign column name', function (string $tableName, string $expected) {
    $this->assertEquals($expected, EmgHelper::getDefaultForeignColumnName($tableName));
})->with('tableNameToForeignColumnNameProvider');

test('Get default join table name', function (string $tableNameOne, string $tableNameTwo, string $expected) {
    $this->assertEquals($expected, EmgHelper::getDefaultJoinTableName($tableNameOne, $tableNameTwo));
})->with('tableNamesProvider');

test('Is column unique', function () {
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

    $this->assertTrue(EmgHelper::isColumnUnique($tableMock, 'column_0'));
});

test('Is column unique two index columns', function () {
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

    $this->assertFalse(EmgHelper::isColumnUnique($tableMock, 'column_0'));
});

test('Is column unique index not unique', function () {
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

    $this->assertFalse(EmgHelper::isColumnUnique($tableMock, 'column_0'));
});

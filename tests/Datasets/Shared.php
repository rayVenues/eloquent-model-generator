<?php

use Illuminate\Database\Eloquent\Model;

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
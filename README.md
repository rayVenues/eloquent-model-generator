# Eloquent Model Generator

Eloquent Model Generator generates Eloquent models using database schema as a source.

## Version 1.0.0

Version 1.0.0 has been released.

## Installation

Step 1. Configure your database connection. Run your migrations.

Step 2. Add Eloquent Model Generator to your project:

```BASH
composer require rayvenues/eloquent-model-generator --dev
```

## Usage

Use

```BASH
php artisan ray:generate:model User
```

to generate a model class. Generator will look for table named `users` and generate a model for it.

### table-name

Use `--table-name` option to specify another table name:

```BASH
php artisan ray:generate:model User --table-name=user
```

In this case generated model will contain `protected $table = 'user'` property.

### output-path

Generated file will be saved into `app/Models` directory of your application and have `App\Models` namespace by default.
If you want to change the destination and namespace, supply the `output-path` and `namespace` options respectively:

```BASH
php artisan ray:generate:model User --output-path=/full/path/to/output/directory --namespace=Your\\Custom\\Models\\Place
```

`--output-path` can be absolute path or relative to project's `app` directory. Absolute path must start with `/`:

- `/var/www/html/app/Models` - absolute path
- `Custom/Models` - relative path, will be transformed to `/var/www/html/app/Custom/Models` (assuming your project app
  directory is `/var/www/html/app`)

### base-class-name

By default, generated class will be extended from `Illuminate\Database\Eloquent\Model`. To change the base class
specify `base-class-name` option:

```BASH
php artisan ray:generate:model User --base-class-name=Custom\\Base\\Model
```

### no-backup

If `User.php` file already exist, it will be renamed into `User.php~` first and saved at the same directory.
Unless `--no-backup` option is specified:

```BASH
php artisan ray:generate:model User --no-backup
```

### no-timestamps

If you want to disable timestamps for the model, specify `--no-timestamps` option:

```BASH
php artisan ray:generate:model User --no-timestamps
```

### date-format

If you want to specify date format for the model, specify `--date-format` option:

```BASH
php artisan ray:generate:model User --date-format='Y-m-d'
``` 

### connection

If you want to specify connection name for the model, specify `--connection` option:

```BASH
php artisan ray:generate:model User --connection='mysql'
```

### Overriding default options

Instead of specifying options each time when executing the command you can publish the config file
by executing the following command:

```BASH
php artisan vendor:publish --provider="Ray\EloquentModelGenerator\Provider\GeneratorServiceProvider"
```

This will create a file named `eloquent_model_generator.php` at project's `config` directory. You can
modify the file with your own default values:

```php
<?php

return [
    'namespace' => 'App',
    'base_class_name' => \Illuminate\Database\Eloquent\Model::class,
    'output_path' => null,
    'no_timestamps' => null,
    'date_format' => null,
    'connection' => null,
    'no_backup' => null,
    'db_types' => null,
];
```

### Registering custom database types

If running a command leads to an error

```
[Doctrine\DBAL\DBALException]
Unknown database type <TYPE> requested, Doctrine\DBAL\Platforms\MySqlPlatform may not support it.
```

it means that you must register your type `<TYPE>` at your `config/eloquent_model_generator.php`:

```PHP
return [
    // ...
    'db_types' => [
        '<TYPE>' => 'string',
    ],
];
```

### Usage example

Table `user`:

```mysql
CREATE TABLE `users`
(
    `id`       int(10) unsigned NOT NULL AUTO_INCREMENT,
    `role_id`  int(10) unsigned NOT NULL,
    `username` varchar(50)      NOT NULL,
    `email`    varchar(100)     NOT NULL,
    PRIMARY KEY (`id`),
    KEY `role_id` (`role_id`),
    CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
```

Command:

```BASH
php artisan ray:generate:model User
```

Result:

App\Models\User.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $role_id
 * @property string $username
 * @property string $email
 * @property Role $role
 */
class User extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'username', 'email'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Role');
    }
}
```

## Generating models for all tables

Command `ray:generate:models` will generate models for all tables in the database. It accepts all options available
for `ray:generate:model` along with the `skip-table` option.

### skip-table

Specify one or multiple table names to skip:

```BASH
php artisan ray:generate:models --skip-table=users --skip-table=roles
```

OR specify a string of tables separated by a comma:

```BASH
php artisan ray:generate:models --skip-table="users,roles"
```

Note that table names must be specified without prefix if you have one configured.

## Customization

You can hook into the process of model generation by adding your own instances
of `Ray\EloquentModelGenerator\Processor\ProcessorInterface` and tagging it
with `GeneratorServiceProvider::PROCESSOR_TAG`.

Imagine you want to override Eloquent's `perPage` property value.

```php
class PerPageProcessor implements ProcessorInterface
{
    public function process(EloquentModel $model, Config $config): void
    {
        $propertyModel = new PropertyModel('perPage', 'protected', 20);
        $dockBlockModel = new DocBlockModel('The number of models to return for pagination.', '', '@var int');
        $propertyModel->setDocBlock($dockBlockModel);
        $model->addProperty($propertyModel);
        
        $propertyModel = new PropertyModel('guarded', 'protected', []);
        $dockBlockModel = new DocBlockModel('¡Tengo miedo!.', '', '@var array');
        $propertyModel->setDocBlock($dockBlockModel);
        $model->addProperty($propertyModel);

    }

    public function getPriority(): int
    {
        return 8;
    }
}
```

`getPriority` determines the order of when the processor is called relative to other processors.

In your service provider:

```php
public function register()
{
    $this->app->tag([PerPageProcessor::class], [GeneratorServiceProvider::PROCESSOR_TAG]);
}
```

After that, generated models will contain the following code:

```php
...
/**
 * The number of models to return for pagination.
 * 
 * @var int
 */
protected $perPage = 20;

/**
 * ¡Tengo miedo!.
 * 
 * @var array
 */
protected $guarded = [];
...
```

<?php

namespace Ray\EloquentModelGenerator\Provider;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Ray\EloquentModelGenerator\Command\GenerateModelCommand;
use Ray\EloquentModelGenerator\Command\GenerateModelsCommand;
use Ray\EloquentModelGenerator\EventListener\GenerateCommandEventListener;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Processor\ClassDefinitionProcessor;
use Ray\EloquentModelGenerator\Processor\CustomPrimaryKeyProcessor;
use Ray\EloquentModelGenerator\Processor\ModelPropertyProcessor;
use Ray\EloquentModelGenerator\Processor\FieldProcessor;
use Ray\EloquentModelGenerator\Processor\NamespaceProcessor;
use Ray\EloquentModelGenerator\Processor\RelationProcessor;
use Ray\EloquentModelGenerator\Processor\SoftDeleteProcessor;
use Ray\EloquentModelGenerator\Processor\TableNameProcessor;
use Ray\EloquentModelGenerator\Processor\TableTimestampsProcessor;
use Ray\EloquentModelGenerator\TypeRegistry;

class GeneratorServiceProvider extends ServiceProvider
{
    public const PROCESSOR_TAG = 'eloquent_model_generator.processor';

    public function register(): void
    {
        $this->commands([
            GenerateModelCommand::class,
            GenerateModelsCommand::class,
        ]);

        $this->app->singleton(TypeRegistry::class);
        $this->app->singleton(GenerateCommandEventListener::class);

        $this->app->tag([
            FieldProcessor::class,
            NamespaceProcessor::class,
            RelationProcessor::class,
            ModelPropertyProcessor::class,
            ClassDefinitionProcessor::class,
            CustomPrimaryKeyProcessor::class,
            SoftDeleteProcessor::class,
            TableTimestampsProcessor::class,
        ], self::PROCESSOR_TAG);

        $this->app->bind(Generator::class, function ($app) {
            return new Generator($app->tagged(self::PROCESSOR_TAG));
        });
    }

    public function boot(): void
    {
        Event::listen(CommandStarting::class, [GenerateCommandEventListener::class, 'handle']);

        $this->publishes([
            __DIR__ . '/../Config/eloquent-model-generator.php' => config_path('eloquent-model-generator.php'),
        ]);
    }
}

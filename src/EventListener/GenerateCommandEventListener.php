<?php

namespace Ray\EloquentModelGenerator\EventListener;

use Doctrine\DBAL\Exception;
use Illuminate\Console\Events\CommandStarting;
use Ray\EloquentModelGenerator\TypeRegistry;

class GenerateCommandEventListener
{
    private const SUPPORTED_COMMANDS = [
        'ray:generate:model',
        'ray:generate:models',
    ];

    public function __construct(private readonly TypeRegistry $typeRegistry) {}

    /**
     * @throws Exception
     */
    public function handle(CommandStarting $event): void
    {
        if (!in_array($event->command, self::SUPPORTED_COMMANDS)) {
            return;
        }

        $userTypes = config('eloquent_model_generator.db_types', []);
        foreach ($userTypes as $type => $value) {
            $this->typeRegistry->registerType($type, $value);
        }
    }
}

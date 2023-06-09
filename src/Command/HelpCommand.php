<?php

namespace Ray\EloquentModelGenerator\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HelpCommand extends Command
{
    protected $name = 'ray:help';

    protected $description = 'Display the parameters for a command with the given signature';

    public function handle()
    {
        $commandSignature = $this->argument('command');

        if ($commandSignature) {
            $this->displayCommandParameters($commandSignature);
        } else {
            $this->displayAllCommands();
        }
    }

    protected function displayAllCommands()
    {
        $commands = Artisan::all();

        $this->info('Available commands:');
        foreach ($commands as $command => $class) {
            $this->line($command);
        }
    }

    protected function displayCommandParameters($commandSignature)
    {
        $command = Artisan::all()[$commandSignature] ?? null;

        if (! $command) {
            $this->error("Command '{$commandSignature}' not found.");
            return;
        }

        $this->info("Parameters for command '{$commandSignature}':");

        $this->line('Signature: ' . $command->getSignature());
        $this->line('Description: ' . $command->getDescription());
        $this->line('Options:');
        $this->displayCommandOptions($command);
        $this->line('Arguments:');
        $this->displayCommandArguments($command);
    }

    protected function displayCommandOptions($command)
    {
        $options = $command->getDefinition()->getOptions();

        if (empty($options)) {
            $this->line('None');
        } else {
            foreach ($options as $option) {
                $this->line('-' . $option->getShortcut() . '|--' . $option->getName());
                $this->line('  Description: ' . $option->getDescription());
                $this->line('  Value Required: ' . ($option->isValueRequired() ? 'Yes' : 'No'));
                $this->line('  Default Value: ' . ($option->getDefault() ?: 'None'));
            }
        }
    }

    protected function displayCommandArguments($command)
    {
        $arguments = $command->getDefinition()->getArguments();

        if (empty($arguments)) {
            $this->line('None');
        } else {
            foreach ($arguments as $argument) {
                $this->line($argument->getName());
                $this->line('  Description: ' . $argument->getDescription());
                $this->line('  Required: ' . ($argument->isRequired() ? 'Yes' : 'No'));
                $this->line('  Default Value: ' . ($argument->getDefault() ?: 'None'));
            }
        }
    }
}

<?php

namespace Ray\EloquentModelGenerator\Command;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Symfony\Component\Console\Input\InputArgument;

class GenerateModelCommand extends Command
{
    use GenerateCommandTrait;

    protected $name = 'ray:generate:model';
    protected $description = 'Generate a model class based on a database table';

    public function __construct(
        private readonly Generator       $generator,
        private readonly DatabaseManager $databaseManager)
    {
        parent::__construct();
    }

    /**
     * @throws GeneratorException
     */
    public function handle(): void
    {
        $config = $this->createConfig();
        $config->setClassName($this->argument('class-name'));
        Prefix::setPrefix($this->databaseManager->connection($config->getConnection())->getTablePrefix());

        $model = $this->generator->generateModel($config);
        $this->saveModel($model);

        $this->output->writeln(sprintf('Model %s generated', $model->getName()->getName()));
    }

    protected function getArguments(): array
    {
        return [
            ['class-name', InputArgument::REQUIRED, 'Model class name'],
        ];
    }

    protected function getOptions(): array
    {
        return $this->getCommonOptions();
    }
}

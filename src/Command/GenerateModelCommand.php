<?php

namespace Ray\EloquentModelGenerator\Command;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Contracts\Console\PromptsForMissingInput as PromptsForMissingInputContract;

class GenerateModelCommand extends Command implements PromptsForMissingInputContract
{
    use GenerateCommandTrait;

    protected $name = 'ray:generate:model';

    protected $description = 'Generate a model class based on a database table. The model name will be the same as the table name.';

    public function __construct(
        private readonly Generator       $generator,
        private readonly DatabaseManager $databaseManager)
    {
        parent::__construct();
    }

    /**
     */
    public function handle(): void
    {
        try {
            $config = $this->createConfig();
            $config->setClassName($this->argument('model-name'));
            Prefix::setPrefix($this->databaseManager->connection($config->getConnection())->getTablePrefix());

            $model = $this->generator->generateModel($config);
            $this->saveModel($model);

            $this->output->writeln(sprintf('Model %s generated', $model->getName()->getName()));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

    }

    protected function getArguments(): array
    {
        return [
            ['model-name', InputArgument::REQUIRED, 'Model name'],
        ];
    }

    protected function getOptions(): array
    {
        return $this->getCommonOptions();
    }

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}

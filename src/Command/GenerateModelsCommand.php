<?php

namespace Ray\EloquentModelGenerator\Command;

use Doctrine\DBAL\Exception;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Generator;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Helper\Prefix;
use Symfony\Component\Console\Input\InputOption;

class GenerateModelsCommand extends Command
{
    use GenerateCommandTrait;

    protected $name = 'ray:generate:models';
    protected $description = 'Generate all model classes based on a database table. You can skip tables with --skip-table option.';

    public function __construct(private readonly Generator $generator, private readonly DatabaseManager $databaseManager)
    {
        parent::__construct();
    }

    /**
     * @throws GeneratorException
     * @throws Exception
     */
    public function handle(): void
    {
        $config = $this->createConfig();
        Prefix::setPrefix($this->databaseManager->connection($config->getConnection())->getTablePrefix());

        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();
        $tables = $schemaManager->listTables();
        $skipTables = $this->option('skip-table');
        if (count($skipTables) === 1 && str_contains($skipTables[0], ',')) {
            $skipTables = explode(',', $skipTables[0]);
        }
        foreach ($tables as $table) {
            $tableName = Prefix::remove($table->getName());
            if (in_array($tableName, $skipTables)) {
                continue;
            }

            $config->setClassName(EmgHelper::getClassNameByTableName($tableName));
            $model = $this->generator->generateModel($config);
            $this->saveModel($model);

            $this->output->writeln(sprintf('Model %s generated', $model->getName()->getName()));
        }
    }

    protected function getOptions(): array
    {
        return array_merge(
            $this->getCommonOptions(),
            [
                ['skip-table', 'sk', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Tables to skip generating models for', null],
            ],
        );
    }
}

<?php

namespace Ray\EloquentModelGenerator\Command;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Symfony\Component\Console\Input\InputOption;

trait GenerateCommandTrait
{
    /**
     * @throws Exception
     */
    protected function createConfig(): Config
    {
        return (new Config())
            ->setTimestampsDisabled($this->option('no-timestamps'))
            ->setBaseClassName($this->option('base-class-name'))
            ->setClassType($this->option('class-type'))
            ->setConnection($this->option('connection'))
            ->setDateFormat($this->option('date-format'))
            ->setImplements($this->option('implements'))
            ->setNamespace($this->option('namespace'))
            ->setOutputPath($this->option('output-path'))
            ->setPerPage($this->option('per-page'))
            ->setTableName($this->option('table-name'))
            ->setUsesTrait($this->option('uses-trait'))
            ->addUses($this->option('uses'));
    }

    /**
     * @throws GeneratorException
     */
    protected function saveModel(EloquentModel $model): void
    {
        $content = $model->render();

        $outputFilepath = $this->resolveOutputPath() . '/' . $model->getName()->getName() . '.php';
        if ($this->option('no-backup') !== null && file_exists($outputFilepath)) {
            rename($outputFilepath, $outputFilepath . '~');
        }
        $bytesWritten = file_put_contents($outputFilepath, $content);
        if ($bytesWritten === false) {
            throw new GeneratorException(sprintf('Could not write to %s.', $outputFilepath));
        }
    }

    /**
     * @throws GeneratorException
     */
    protected function resolveOutputPath(): string
    {
        $path = $this->option('output-path');
        if ($path === null) {
            $path = App::path('Models');
        } elseif (! str_starts_with($path, '/')) {
            $path = App::path($path);
        }

        if (! is_dir($path)) {
            if (! mkdir($path, 0777, true)) {
                throw new GeneratorException(sprintf('Could not create directory %s.', $path));
            }
        }

        if (! is_writeable($path)) {
            throw new GeneratorException(sprintf('%s is not writeable.', $path));
        }

        return $path;
    }

    protected function getCommonOptions(): array
    {
        return [
            ['base-class-name', 'bc', InputOption::VALUE_OPTIONAL, 'Model parent class', config('eloquent_model_generator.base_class_name', Model::class)],
            ['uses-trait', 'ut', InputOption::VALUE_OPTIONAL, 'Model use trait.', config('eloquent_model_generator.uses_trait')],
            ['uses', 'us', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Model base class uses', config('eloquent_model_generator.base_class_uses')],
            ['class-type', 'ct', InputOption::VALUE_OPTIONAL, 'Set Model Class type (abstract or final)', config('eloquent_model_generator.class_type', false)],
            ['connection', 'cn', InputOption::VALUE_OPTIONAL, 'Connection property', config('eloquent_model_generator.connection')],
            ['date-format', 'df', InputOption::VALUE_OPTIONAL, 'The storage format of the model\'s date columns.', config('eloquent_model_generator.date_format')],
            ['implements', 'im', InputOption::VALUE_OPTIONAL, 'Set Model Class implements', config('eloquent_model_generator.implements', false)],
            ['namespace', 'ns', InputOption::VALUE_OPTIONAL, 'Namespace of the model', config('eloquent_model_generator.namespace', 'App\Models')],
            ['no-backup', 'nb', InputOption::VALUE_OPTIONAL, 'Backup existing model', config('eloquent_model_generator.no_backup')],
            ['no-timestamps', 'nt', InputOption::VALUE_OPTIONAL, 'Indicates if the model should NOT be timestamped.', config('eloquent_model_generator.no_timestamps')],
            ['output-path', 'op', InputOption::VALUE_OPTIONAL, 'Directory to store generated model', config('eloquent_model_generator.output_path')],
            ['table-name', 'tn', InputOption::VALUE_OPTIONAL, 'The table associated with the model', null],
            ['per-page', 'pp', InputOption::VALUE_OPTIONAL, 'The number of models to return for pagination.'],
        ];
    }
}

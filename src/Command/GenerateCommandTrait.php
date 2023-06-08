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
            ->setTableName($this->option('table-name'))
            ->setNamespace($this->option('namespace'))
            ->setOutputPath($this->option('output-path'))
            ->setBaseClassName($this->option('base-class-name'))
            ->setClassType($this->option('class-type'))
            ->setImplements($this->option('implements'))
            ->setNoTimestamps($this->option('no-timestamps'))
            ->setDateFormat($this->option('date-format'))
            ->setConnection($this->option('connection'));
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
            throw new GeneratorException(sprintf('Could not write to %s', $outputFilepath));
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
                throw new GeneratorException(sprintf('Could not create directory %s', $path));
            }
        }

        if (! is_writeable($path)) {
            throw new GeneratorException(sprintf('%s is not writeable', $path));
        }

        return $path;
    }

    protected function getCommonOptions(): array
    {
        return [
            ['table-name', 'tn', InputOption::VALUE_OPTIONAL, 'Name of the table to use', null],
            ['output-path', 'op', InputOption::VALUE_OPTIONAL, 'Directory to store generated model', config('eloquent_model_generator.output_path')],
            ['namespace', 'ns', InputOption::VALUE_OPTIONAL, 'Namespace of the model', config('eloquent_model_generator.namespace', 'App\Models')],
            ['base-class-name', 'bc', InputOption::VALUE_OPTIONAL, 'Model parent class', config('eloquent_model_generator.base_class_name', Model::class)],
            ['no-timestamps', 'ts', InputOption::VALUE_OPTIONAL, 'Set timestamps property to false', config('eloquent_model_generator.no_timestamps', false)],
            ['date-format', 'df', InputOption::VALUE_OPTIONAL, 'dateFormat property', config('eloquent_model_generator.date_format')],
            ['connection', 'cn', InputOption::VALUE_OPTIONAL, 'Connection property', config('eloquent_model_generator.connection')],
            ['no-backup', 'b', InputOption::VALUE_OPTIONAL, 'Backup existing model', config('eloquent_model_generator.no_backup', false)],
            ['class-type', 'ct', InputOption::VALUE_OPTIONAL, 'Set Model Class type (abstract or final)', config('eloquent_model_generator.class_type', false)],
            ['implements', 'im', InputOption::VALUE_OPTIONAL, 'Set Model Class implements', config('eloquent_model_generator.implements', false)],
        ];
    }
}

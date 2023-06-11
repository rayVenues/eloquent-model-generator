<?php

namespace Ray\EloquentModelGenerator\Processor;

use Exception;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Model\ClassNameModel;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\Model\UseClassModel;

class ClassDefinitionProcessor implements ProcessorInterface
{
    /**
     * @throws GeneratorException
     * @throws Exception
     */
    public function process(EloquentModel $model, Config $config): void
    {
        $className = $config->getClassName();
        $baseClassName = $config->getBaseClassName();
        $classType = $config->getClassType();

        $model
            ->setName(new ClassNameModel($className, EmgHelper::getShortClassName($baseClassName), $classType));
        if ($config->getUses() !== null) {
            foreach ($config->getUses() as $use) {
                $model->addUses($use);
            }
        } else {
            $model->addUses(new UseClassModel(ltrim($baseClassName, '\\')));
        }
    }

    public function getPriority(): int
    {
        return 10;
    }
}

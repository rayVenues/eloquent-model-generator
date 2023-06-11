<?php

namespace Ray\EloquentModelGenerator\Processor;

use Exception;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Model\ClassNameModel;
use Ray\EloquentModelGenerator\Model\DocBlockModel;
use Ray\EloquentModelGenerator\Model\EloquentModel;
use Ray\EloquentModelGenerator\Model\PropertyModel;
use Ray\EloquentModelGenerator\Model\UseClassModel;
use Ray\EloquentModelGenerator\Model\UseTraitModel;

class UsesTraitProcessor implements ProcessorInterface
{
    /**
     * @param EloquentModel $model
     * @param Config $config
     */
    public function process(EloquentModel $model, Config $config): void
    {
        if ($trait = $config->getUsesTrait()) {
            $traitModel = new UseTraitModel($trait);
            $model->addTrait($traitModel);
        }
    }

    public function getPriority(): int
    {
        return 10;
    }
}

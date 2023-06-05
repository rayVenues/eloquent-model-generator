<?php

namespace Ray\EloquentModelGenerator\Processor;

use Ray\EloquentModelGenerator\Model\NamespaceModel;
use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Model\EloquentModel;

class NamespaceProcessor implements ProcessorInterface
{
    public function process(EloquentModel $model, Config $config): void
    {
        $model->setNamespace(new NamespaceModel($config->getNamespace()));
    }

    public function getPriority(): int
    {
        return 6;
    }
}

<?php

namespace Ray\EloquentModelGenerator\Processor;

use Ray\EloquentModelGenerator\Config\Config;
use Ray\EloquentModelGenerator\Model\EloquentModel;

interface ProcessorInterface
{
    public function process(EloquentModel $model, Config $config): void;
    public function getPriority(): int;
}

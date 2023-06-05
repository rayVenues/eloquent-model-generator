<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class BaseMethodModel
 * @package App\CodeGenerator\Model
 */
abstract class BaseMethodModel extends RenderableModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var ArgumentModel[]
     */
    protected $arguments = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArgumentModel[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param ArgumentModel $argument
     *
     * @return $this
     */
    public function addArgument(ArgumentModel $argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderArguments()
    {
        $result = '';
        if ($this->arguments) {
            $arguments = [];
            foreach ($this->arguments as $argument) {
                $arguments[] = $argument->render();
            }

            $result .= implode(', ', $arguments);
        }

        return $result;
    }
}

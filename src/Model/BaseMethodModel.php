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
    protected string $name;

    /**
     * @var ArgumentModel[]
     */
    protected array $arguments = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArgumentModel[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param ArgumentModel $argument
     *
     * @return $this
     */
    public function addArgument(ArgumentModel $argument): static
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderArguments(): string
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

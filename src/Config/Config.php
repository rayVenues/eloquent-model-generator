<?php

namespace Ray\EloquentModelGenerator\Config;

use Exception;
use Ray\EloquentModelGenerator\Helper\EmgHelper;
use Ray\EloquentModelGenerator\Helper\NamespaceValidator;
use Ray\EloquentModelGenerator\Model\Traits\ClassTypeModifierTrait;

class Config
{
    use ClassTypeModifierTrait;

    private ?bool $noBackup = false;
    private ?bool $timestampsDisabled = false;
    private ?string $baseClassName = null;
    private ?string $className = null;
    private ?string $connection = null;
    private ?string $dateFormat = null;
    private ?string $namespace = null;
    private ?string $tableName = null;
    private ?string $path = null;
    private ?string $implements;

    private ?string $uses = null;
    private ?int $perPage = null;

    public function getUses(): ?string
    {
        return $this->uses;
    }

    public function setUses(?string $uses): self
    {
        $this->uses = $uses;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(?string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getOutputPath(): ?string
    {
        return $this->path;
    }

    /**
     * @throws Exception
     */
    public function setOutputPath(?string $path): self
    {
        if ($path) {
            $this->namespace = NamespaceValidator::pathToModelNamespace($path);
        }
        $this->path = $path;

        return $this;
    }

    public function getBaseClassName(): ?string
    {
        return $this->baseClassName;
    }

    public function setBaseClassName(?string $baseClassName): self
    {
        $this->baseClassName = $baseClassName;

        return $this;
    }

    public function setTimestampsDisabled(?bool $flag): self
    {
        $this->timestampsDisabled = $flag;

        return $this;

    }

    public function getTimestampsDisabled(): bool
    {
        return $this->timestampsDisabled ?? false;
    }

    public function getDateFormat(): ?string
    {
        return $this->dateFormat;
    }

    public function setDateFormat(?string $dateFormat): self
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    public function getConnection(): ?string
    {
        return $this->connection;
    }

    public function setConnection(?string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public function getNoBackup(): ?bool
    {
        return $this->noBackup;
    }

    public function setNoBackup(): self
    {
        $this->noBackup = true;

        return $this;
    }

    public function setImplements(string $interface): self
    {
        $this->implements = $interface;

        return $this;
    }

    public function getImplements(): string
    {
        return $this->implements;
    }

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function setPerPage(?int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }
}

<?php

namespace App\Library\NamingStrategy;

use Doctrine\ORM\Mapping\NamingStrategy;

class CustomNamingStrategy implements NamingStrategy
{
    public function classToTableName($className): string
    {
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }

        return strtolower($className);
    }

    public function propertyToColumnName($propertyName, $className = null): string
    {
        if ($className !== null) {
            return sprintf('%s_%s', $this->classToTableName($className), $this->underscore($propertyName));
        }
        return $this->underscore($propertyName);
    }

    public function embeddedFieldToColumnName(
        $propertyName,
        $embeddedColumnName,
        $className = null,
        $embeddedClassName = null
    ): string {
        return sprintf('%s_%s', $this->underscore($propertyName), $embeddedColumnName);
    }

    public function referenceColumnName(): string
    {
        return 'id';
    }

    public function joinColumnName($propertyName): string
    {
        return sprintf('%s_%s', $this->underscore($propertyName), $this->referenceColumnName());
    }

    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null): string
    {
        return sprintf('%s_%s', $this->classToTableName($sourceEntity), $this->classToTableName($targetEntity));
    }

    public function joinKeyColumnName($entityName, $referencedColumnName = null): string
    {
        return sprintf(
            '%s_%s',
            $this->classToTableName($entityName),
            ($referencedColumnName ?: $this->referenceColumnName())
        );
    }

    private function underscore(string $string): string
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $string));
    }
}

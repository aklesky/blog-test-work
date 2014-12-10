<?php

namespace app\code;

use App\Code\Interfaces\IColumn;

abstract class Column implements IColumn
{
    const NO = 'no';
    const YES = 'yes';

    protected $value = null;

    protected $previousValue = null;

    protected $required = false;

    protected $columnName = null;

    protected $columnType = null;

    public function getPrevious()
    {
        return $this->previousValue;
    }

    public function getName()
    {
        return $this->columnName;
    }

    public function setName($value)
    {
        $this->columnName = $value;

        return $this;
    }

    public function isEmptyAllowed()
    {
        if ($this->isRequired() && $this->getValue() == null)
            return false;

        return true;
    }

    public function isRequired()
    {
        return ($this->required == self::NO);
    }

    public function setRequired($value)
    {
        $this->required = mb_strtolower($value);

        return $this;
    }

    public function getValue()
    {
        return trim($this->value);
    }

    public function setValue($value)
    {
        $this->previousValue = $this->value;
        $this->value = $value;

        return $this;
    }

    public function getType()
    {
        return $this->columnType;
    }

    public function setType($value)
    {
        $this->columnType = $value;

        return $this;
    }

    public function getDefault()
    {
        // TODO: Implement getDefault() method.
    }

    public function setDefault($value)
    {
        // TODO: Implement setDefault() method.
    }

    public function restore()
    {
        if (!empty($this->previousValue)) {
            $this->value = $this->previousValue;
        }

        return $this;
    }
}
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

    public function isRequired()
    {
        return ($this->required == self::NO);
    }

    public function setRequired($value)
    {
        $this->required = mb_strtolower($value);

        return $this;
    }

    public function getType()
    {
        // TODO: Implement getType() method.
    }

    public function setType($value)
    {
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->previousValue = $this->value;
        $this->value = $value;

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
        if(!empty($this->previousValue)){
            $this->value = $this->previousValue;
        }
        return $this;
    }
}
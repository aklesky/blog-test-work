<?php
namespace app\code\columns;

use App\Code\Column;

class Datetime extends Column
{

    public function setValue($value)
    {
        $this->value = date('Y-m-d H:i:s', strtotime($value));
        return $this;
    }

    public function getValue()
    {
        if(empty($this->value) || $this->value == $this->getDefault()) {
            $this->value = date('Y-m-d H:i:s');
        }
        return $this->value;
    }
}
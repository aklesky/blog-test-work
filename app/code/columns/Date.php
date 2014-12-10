<?php

namespace app\code\columns;

class Date extends Datetime
{
    public function setValue($value)
    {
        $strToTime = strtotime($value);
        if($strToTime < time())
            $strToTime = time();

        $this->value = date('Y-m-d', $strToTime);
        return $this;
    }
}
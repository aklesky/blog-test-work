<?php

namespace App\Code\Acl;

use App\Code\AccessLayer;
use app\code\User;

class Session extends AccessLayer
{

    public function canAccess()
    {
        if ($this->isAllowedToAll())
            return true;

        return $this->isAllowed();
    }

    protected function isAllowed()
    {
        if ($this->isAllow() && User::isOnline()) {
            return true;
        } else if ($this->isAllow() && !User::isOnline()) {
            return false;
        } else if ($this->isDisallow() && User::isOnline()) {
            return false;
        } else if ($this->isDisallow() && !User::isOnline()) {
            return true;
        }

        return false;
    }

    protected function isAllow()
    {
        return $this->getAllow() === mb_strtolower(Session::getName());
    }

    protected function isDisallow()
    {
        return $this->getDisallow() === mb_strtolower(Session::getName());
    }
}
<?php

namespace App\Code\Acl;

use App\Code\AccessLayer;

class Session extends AccessLayer
{

    protected $sessionKey = 'UserSession';

    public function canAccess()
    {
        if($this->isAllowedToAll())
            return true;

        if($this->isAllowed())
            return true;

        if($this->isDisallowed())
            return false;

        return false;
    }

    protected function isSessionActive()
    {
        return !empty($_SESSION[$this->sessionKey]) &&
        !empty($_SESSION[$this->sessionKey]['userId']);
    }

    protected function isAllowed()
    {
        return $this->getAllow() === mb_strtolower(Session::getName())
        && $this->isSessionActive();
    }

    protected function isDisallowed()
    {
        return $this->getDisallow() === mb_strtolower(Session::getName())
        && $this->isDisallowed();
    }

} 
<?php

namespace App\Code\Acl;

use App\Code\AccessLayer;

class Session extends AccessLayer
{

    protected $sessionKey = 'UserSession';

    /**
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    public function canAccess()
    {
        if ($this->isAllowedToAll())
            return true;

        return $this->isAllowed();
    }

    protected function isSessionActive()
    {
        return !empty($_SESSION[$this->sessionKey]) &&
        !empty($_SESSION[$this->sessionKey]['userId']);
    }

    protected function isAllow()
    {
        return $this->getAllow() === mb_strtolower(Session::getName());
    }

    protected function isDisallow()
    {
        return $this->getDisallow() === mb_strtolower(Session::getName());
    }

    protected function isAllowed()
    {
        if($this->isAllow() && $this->isSessionActive()) {
            return true;
        } else if ($this->isDisallow() && $this->isSessionActive())
        {
            return false;
        }
        return false;
    }
}
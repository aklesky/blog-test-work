<?php

namespace app\code;

class User extends Object
{
    protected $sessionKey = 'UserSession';

    /**
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    protected function setUserSessionId($id){
        if(empty($id))
            return false;
        $_SESSION[$this->sessionKey]['userId'] = $id;
        return $this;
    }

    protected function isSessionActive()
    {
        return !empty($_SESSION[$this->sessionKey]) &&
        !empty($_SESSION[$this->sessionKey]['userId']);
    }

    public static function isOnline()
    {
        return self::getInstance()->isSessionActive();
    }

    public static function setUserSession($id = null)
    {
        return self::getInstance()->setUserSessionId($id);
    }
} 
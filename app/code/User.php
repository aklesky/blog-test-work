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

    public static function isOnline()
    {
        return self::getInstance()->isSessionActive();
    }

    public static function setUserSession($id = null)
    {
        return self::getInstance()->setUserSessionId($id);
    }

    public static function getUserId()
    {
        return self::getInstance()->getUserSessionId();
    }

    protected function setUserSessionId($id)
    {
        if (empty($id))
            return false;
        $_SESSION[$this->sessionKey]['userId'] = $id;

        return $this;
    }

    protected function getUserSessionId()
    {
        if (!$this->isSessionActive())
            return false;

        return $_SESSION[$this->sessionKey]['userId'];
    }

    protected function isSessionActive()
    {
        return !empty($_SESSION[$this->sessionKey]) &&
        !empty($_SESSION[$this->sessionKey]['userId']);
    }
} 
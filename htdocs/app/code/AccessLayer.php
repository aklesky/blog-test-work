<?php

namespace App\Code;

use App\Code\Acl\Session;

class AccessLayer extends Object
{

    protected $securityDescription;

    public function __construct($description = null)
    {
        $this->securityDescription = $description;
    }

    public function canAccess($description = null)
    {
        return Session::getInstance($description)->canAccess();
    }

    protected function isAllowedToAll()
    {
        return $this->getAllow() == null && $this->getDisallow() == null;
    }

    protected function getAllow()
    {
        if (empty($this->securityDescription))
            return null;

        return $this->getPatternBlock('allow', $this->securityDescription);
    }

    protected function getDisallow()
    {
        if (empty($this->securityDescription))
            return null;

        return $this->getPatternBlock('disallow', $this->securityDescription);
    }

    protected function isAllow()
    {
        return false;
    }

    protected function isDisallow()
    {
        return false;
    }

    protected function isAllowed()
    {
        return false;
    }
}
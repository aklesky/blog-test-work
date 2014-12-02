<?php

namespace App\Code;

class Uri extends Object
{

    protected $requestUri;


    public function __construct($uri = null)
    {
        $this->requestUri = parse_url($uri);
    }

    public function getHost()
    {
        return $this->getData('host', 'requestUri');
    }

    public function getPath()
    {
        return $this->clearPath($this->getData('path', 'requestUri'));
    }

    public function getScheme()
    {
        return $this->getData('scheme', 'requestUri');
    }

    private function clearPath($path = null) {
        return preg_replace('#(?<=/)[.+]+(?=/)#',null,
            preg_replace('#(/+){2,}#','/',
                trim($path,"/\\")
            )
        );
    }
} 
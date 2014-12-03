<?php

namespace app\code;

/**
 * Class Controller
 *
 * @package app\code
 * @route /
 */

class Controller extends Object
{

    /** @var  Response */

    protected $response;

    /** @var  Request */

    protected $request;

    public function __construct(Response $response, Request $request)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     * @route /
     */
    public function index()
    {
        echo "Hello World";
    }

    /**
     * @route /404
     */

    public function notFound()
    {
        echo "404";
    }
}
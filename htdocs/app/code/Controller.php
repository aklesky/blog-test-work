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
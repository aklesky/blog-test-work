<?php

namespace App\Code\Controllers;
use app\code\Controller;

/**
 * Class Blogs
 *
 * @package App\Code\Controllers
 * @route /blogs
 */
class Blogs extends Controller
{

    public function index()
    {
        $this->renderResponse();
    }
} 
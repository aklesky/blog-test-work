<?php

namespace App\Code\Controllers;

use app\code\Controller;

/**
 * Class Index
 *
 * @package App\Code\Controllers
 * @route /
 */
class Index extends Controller
{
    /**
     * @route /sitemap.xml
     * @request get|post
     */
    public function sitemap()
    {
    }
}
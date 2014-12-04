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
        $this->Blogs = $this->model->selectAll();

        $this->renderResponse();
    }

    /**
     * @route /sitemap.xml
     */
    public function sitemap()
    {
        /**
         * sitemap template
         */
        $this->renderXmlResponse('<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
 <url>
  <loc>http://www.google.com/</loc>
  <priority>1.000</priority>
 </url>
 <url>
  <loc>http://www.google.com/3dwh_dmca.html</loc>
  <priority>0.5000</priority>
 </url></urlset>');
    }
} 
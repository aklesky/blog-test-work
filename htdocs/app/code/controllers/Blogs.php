<?php

namespace App\Code\Controllers;

use app\code\Controller;

/**
 * Class Blogs
 *
 * @package App\Code\Controllers
 * @route /blog
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

    /**
     * @route /(add|edit)/post
     */
    public function addPost()
    {

        $this->renderResponse('edit_post');
    }

    /**
     * @route /post/add
     */
    public function testPost()
    {
        $model = $this->getModel('BlogPosts');

        if($this->request->isPost()){
            echo '<pre>';
            print_r($this->request->getPost());

            $post = $model->create();


            $post->setData($this->request->getPost());
            $post->save();
            print_r($post);
        }
    }
} 
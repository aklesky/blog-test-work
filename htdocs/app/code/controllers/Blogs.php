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

    /**
     * @request get
     */
    public function index()
    {
        $this->Blogs = $this->model->selectAll();

        $this->renderResponse();
    }

    /**
     * @route /(new|edit)/post(?:/([0-9\-]+)?)?
     * @request get
     * @allow session
     */
    public function editPost($action = null, $id = null)
    {
        if ($action == 'edit') {
            $postModel = $this->getModel('BlogPosts');
            if (!($this->editable = $postModel->selectById($id))) {
                $this->response->Redirect(
                    $this->request->getUrl('blog/new/post')
                );
            }
        }
        $this->renderResponse('edit_post');
    }

    /**
     * @route /(new|edit)(?:/([0-9\-]+)?)?
     * @request get
     * @allow session
     */

    public function editBlog($action = null, $id = null)
    {
        {
            if ($action == 'edit') {
                if (!($this->editable = $this->model->selectById($id))) {
                    $this->response->Redirect(
                        $this->request->getUrl('blog/new/blog')
                    );
                }
            }
        }
        $this->renderResponse('edit_blog');
    }

    /**
     * @route /save
     * @request post
     * @allow session
     */
    public function saveBlog()
    {
        if ($this->request->isAjaxPost()) {

            $data = $this->request->getPost();

            $id = $this->request->getPost('id');

            if (!($blog = $this->model->selectById($id))) {
                $blog = $this->model->create();
            }
            $blog->setData($data);
            if (!$blog->save()) {
                $this->response->JsonResponse($blog->getErrorMessage());
            } else {
                $this->response->JsonResponse();
            }
        }
    }

    /**
     * @route /save/post
     * @request post
     * @allow session
     */
    public function savePost()
    {
        if ($this->request->isAjaxPost()) {
            $model = $this->getModel('BlogPosts');

            $data = $this->request->getPost();

            $id = $this->request->getPost('id');

            if (!($post = $model->selectById($id))) {
                $post = $model->create();
            }

            $post->setData($data);
            if (!$post->save()) {
                $this->response->JsonResponse($post->getErrorMessage());
            } else {
                $this->response->JsonResponse();
            }
        }
    }
} 
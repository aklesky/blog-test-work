<?php

namespace App\Code\Controllers;

use app\code\Controller;
use app\code\User;
use App\Vendors\FileUploader\qqFileUploader;

/**
 * @route /blog
 */
class Blog extends Controller
{

    /**
     * @param int $page
     * @return string|void
     * @request get
     * @route /index(?:/([0-9])?)?
     */
    public function index($page = 0)
    {

        $postModel = $this->getModel('BlogPosts');
        $collection = $postModel->selectBlogPosts(
            5, $page
        )->getPostCollection();

        $this->pages = ceil($postModel->getRowsCount() / 5);

        $this->currentPage = $page;

        $this->collection = $collection;
        $this->user = $postModel->getUser();
        $this->renderResponse('index');
    }

    /**
     * @request post|get
     * @route /view(?:/([A-Za-z0-9\-]+)?)?
     * @param $slugTag
     */
    public function view($slugTag)
    {
        $postModel = $this->getModel('BlogPosts');
        $post = $postModel->selectBlogPostBySlugTag($slugTag);

        $this->post = $post;
        $this->renderResponse('view');
    }

    /**
     * @request get
     * @allow session
     */
    public function posts()
    {
        $model = $this->getModel('BlogPosts');

        $this->blogPosts = $model->selectAll();

        $this->renderResponse('blog_posts');
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
     * @route /edit
     * @request get
     * @allow session
     */

    public function editBlog()
    {
        if (!($this->editable = $this->model->selectBlogByUserId(User::getUserId()))) {
            $this->response->Redirect(
                $this->request->getUrl('blog/new/blog')
            );
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
                $this->response->JsonResponse(
                    array(
                        'id' => $post->getId(),
                        'self' => true
                    )
                );
            }
        }
    }

    /**
     * @route /upload
     * @request post
     * @allow session
     */
    public function upload()
    {
        $post = $this->getModel('BlogPosts')->selectById(
            $this->request->getQuery('postId')
        );

        if ($post == null || $post->getId() == null)
            return null;

        $uploader = new qqFileUploader(array(), 25 * 1024 * 1024);
        $uploader->chunksFolder = Chunks;
        $uploader->inputName = "qqfile";
        $result = $uploader->handleUpload(Uploads);
        $result['uploadName'] = $uploader->getUploadName();
        $post->setPostPicture($uploader->getUploadName());
        $post->save();
        $this->response->JsonResponse(
            $result
        );
    }
} 
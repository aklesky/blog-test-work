<?php

namespace App\Code\Controllers;

use app\code\Controller;
use App\Vendors\FileUploader\qqFileUploader;

/**
 * @route /blog
 * @default /
 */
class Blog extends Controller
{

    /**
     * @route /sitemap.xml
     * @request get|post
     */
    public function sitemap()
    {
        $blogPosts = $this->getModel('BlogPosts');
        $this->collection = $blogPosts->selectAll();
        $this->renderResponseFile('sitemap.phtml');
    }

    /**
     * @param int $page
     * @return string|void
     * @request get
     * @route /index(?:/([0-9])?)?
     * @default /
     */

    public function index($page = 0)
    {
        $this->view->setCanonicalUrl(
            $this->request->getUrl('blog/index'));

        $settings = $this->model->selectFirst();
        $postModel = $this->getModel('BlogPosts');
        $collection = $postModel->selectBlogPosts(
            $settings->getBlogPostLimit(), $page
        )->getPostCollection();

        $this->pages = ceil($postModel->getRowsCount() / 5);

        $this->currentPage = $page;
        $this->settings = $settings;
        $this->collection = $collection;
        $this->user = $postModel->getUser();
        $this->renderResponse('index');
    }

    /**
     * @request post|get
     * @route /view(?:/([A-Za-z0-9\-]+)?)?
     * @param $slugTag
     */
    public function view($slugTag = null)
    {
        if ($slugTag == null) {
            $this->response->Redirect(
                $this->request->getUrl());
        }

        $this->view->setCanonicalUrl(
            $this->request->getUrl('blog/view/' . $slugTag));
        $this->settings = $this->model->selectFirst();
        $postModel = $this->getModel('BlogPosts');
        $post = $postModel->selectBlogPostBySlugTag($slugTag);

        $this->post = $post;
        $this->renderResponse('view');
    }

    /**
     * @route /admin/post/list
     * @request get
     * @allow session
     */
    public function postList()
    {
        $this->settings = $this->model->selectFirst();

        $model = $this->getModel('BlogPosts');

        $this->collection = $model->selectAll();

        $this->renderResponse('admin_posts');
    }

    /**
     * @route /admin/(new|edit)/post(?:/([0-9\-]+)?)?
     * @request get
     * @allow session
     */
    public function editPost($action = null, $id = null)
    {
        $this->settings = $this->model->selectFirst();

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
     * @route /admin/settings
     * @request get
     * @allow session
     */

    public function editBlog()
    {
        $settings = $this->model->selectFirst();
        $this->editable = $settings;
        $this->settings = $settings;
        $this->renderResponse('edit_blog');
    }

    /**
     * @route /admin/save
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
     * @route /admin/save/post
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

            $post->setPostSchedule(
                date("Y-m-d", strtotime($post->getPostSchedule())));

            if (!$post->save()) {
                $this->response->JsonResponse(
                    array(
                        'error' => true,
                        $post->getErrorMessage(),
                        'fields' => $post->getValidation()
                    )
                );
            } else {
                $this->response->JsonResponse(
                    array(
                        'id' => $post->getId(),
                        'redirect' => $this->request->getUrl('blog/admin/edit/post/' . $post->getId())
                    )
                );
            }
        }
    }

    /**
     * @route /admin/upload
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

    /**
     * @route /add/comment
     * @request post
     */
    public function addComment()
    {
        $blogComments = $this->getModel('BlogComments')->create();
        $postId = $this->request->getPost('PostId');
        $blogPosts = $this->getModel('BlogPosts')->create();
        $post = $blogPosts->selectById($postId);

        $blogComments->setData($this->request->getPost());
        $blogComments->save();
        $this->response->JsonResponse(
            array('self' => true)
        );
    }

    /**
     * @route /admin/delete/post(?:/([0-9\-]+)?)?
     * @param null $id
     * @request get
     * @allow session
     */

    public function deletePost($id = null)
    {
        $blogPosts = $this->getModel('BlogPosts');
        $post = $blogPosts->selectById($id);
        if ($post != null && $post->getId() != null) {
            $post->delete();
        }
        $this->response->Redirect(
            $this->request->getUrl('/blog/admin/post/list'));
    }
} 
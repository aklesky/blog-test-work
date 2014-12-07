<?php
namespace App\Code\Controllers;

use app\code\Controller;
use app\code\User;

/**
 * Class Users
 *
 * @package App\Code\Controllers
 * @route /user
 */
class Users extends Controller
{

    /**
     * @route /register
     * @request post
     * @disallow session
     */
    public function add()
    {

        if ($this->request->isAjaxPost()) {
            $user = $this->model->create();
            if ($user->addUser($this->request->getPost())) {
                $this->response->JsonResponse(
                    array('redirect', $this->request->getUrl('blog'))
                );
            }
        }
    }

    /**
     * @route /admin/edit(?:/([0-9]+)?)?
     * @request get
     * @allow session
     */
    public function edit($id = null)
    {
        $this->settings = $this->getModel('Blog')->selectFirst();

        $id = !empty($id) ? $id : User::getUserId();

        $user = $this->model->selectById($id);

        $this->editable = $user;
        $this->renderResponse('edit');
    }

    /**
     * @route /admin/save
     * @request post
     * @allow session
     */
    public function save()
    {

        if ($this->request->isAjaxPost()) {
            $id = $this->request->getPost('id');

            if (!($user = $this->model->selectById($id))) {
                $this->response->JsonResponse(
                    $this->model->getErrorMessage()
                );

                return;
            }
            $user->updateUser($this->request->getPost());
            $this->response->JsonResponse(
                array('self' => true)
            );
        }
    }

    /**
     * @route /login
     * @request post
     * @disallow session
     */
    public function login()
    {
        if ($this->request->isAjaxPost()) {
            if (!($user = $this->model->validateUser(
                $this->request->getPost('email'),
                $this->request->getPost('password')
            ))
            ) {
                $this->response->JsonResponse(
                    $this->model->getErrorMessage()
                );

                return;
            }

            User::setUserSession($user->getId());
            $this->response->JsonResponse(
                array('self' => true)
            );
        }
    }

    /**
     * @route /registration
     * @request get
     * @disallow session
     */

    public function registration()
    {
        $this->settings = $this->getModel('Blog')->selectFirst();
        $this->renderResponse();
    }

    /**
     * @route /logout
     * @request get
     * @allow session
     */

    public function logout()
    {
        session_destroy();
        $this->response->Redirect('/');
    }

    /**
     * @route /admin/list
     * @request get
     * @allow session
     */

    public function usersList()
    {
        $this->settings = $this->getModel('Blog')->selectFirst();
        $this->collection = $this->model->selectAll();
        $this->renderResponse('users');
    }

    /**
     * @route /admin/delete(?:/([0-9]+)?)?
     * @request get
     * @allow session
     */
    public function delete($id = null)
    {
        if (empty($id))
            $this->response->Redirect('/user/admin/list');
        $user = $this->model->selectById($id);
        $user->delete();
        $this->response->Redirect('/user/admin/list');
    }
}

?>
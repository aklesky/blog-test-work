<?php
namespace App\Code\Controllers;

use app\code\Controller;

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
     */
    public function add()
    {

        if ($this->request->isAjaxPost()) {
            $user = $this->model->create();
            if ($user->addUser($this->request->getPost())) {
            }
        }
    }

    /**
     * @route /edit(?:/(.*)?)?
     */
    public function edit()
    {
        $id = 8;
        if (!($user = $this->model->selectById($id)))
            $this->response->Redirect('/blog/new/post');
        $this->editable = $user;
        $this->renderResponse();
    }

    /**
     * @route /save
     */
    public function save()
    {
        if ($this->request->isAjaxPost()) {
            $id = 8;
            if (!($user = $this->model->selectById($id))) {
                $this->response->JsonResponse(
                    $this->model->getErrorMessage()
                );

                return;
            }
            $user->updateUser($this->request->getPost());
            $this->response->JsonResponse();
        }
    }

    /**
     * @route /login
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

            $this->response->JsonResponse();
        }
    }

    /**
     * @route /registration
     */

    public function registration()
    {
        $this->renderResponse();
    }
}

?>
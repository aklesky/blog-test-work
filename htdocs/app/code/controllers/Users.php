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
     * @route /
     */
    public function index()
    {
        echo "Users";
    }

    /**
     * @route /add
     */
    public function add()
    {

        if($this->request->isAjaxRequest() && $this->request->isPost()) {
            $user = $this->model->create();
            $user->addUser($this->request->getPost());
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
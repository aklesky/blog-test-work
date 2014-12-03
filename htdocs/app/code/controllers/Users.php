<?php
namespace App\Code\Controllers;
use app\code\Controller;

/**
 * Class Users
 *
 * @package App\Code\Controllers
 * @route /users
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
     * @route /add(:?)
     */
    public function add(){

    }
}
?>
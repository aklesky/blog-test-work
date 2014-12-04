<?php

namespace app\code;

/**
 * Class Controller
 *
 * @package app\code
 * @route /
 */

class Controller extends Object
{

    /** @var  Response */

    protected $response;

    /** @var  Request */

    protected $request;

    /** @var  View */

    protected $view;

    /**
     * @param Response $response
     * @param Request $request
     * @param View $view
     */
    public function __construct(Response $response, Request $request, View $view)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
    }

    protected function renderResponse($view = null)
    {
        $this->response->ViewResponse(
            $this->view->setView($view, self::getName())
        );
    }

    /**
     * @return string
     * @route /
     */
    public function index()
    {
        $this->renderResponse(404);
    }

    /**
     * @route /404
     */

    public function notFound()
    {
        $this->renderResponse(404);
    }
}
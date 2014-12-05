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

    /** @var  ModelAdapter */

    protected $model;

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
        $this->getModel();
    }

    /**
     * @param null $model
     * @return null| ModelAdapter
     */
    protected function getModel($model = null)
    {
        if ($model != null)
            return App::getModel($model);

        return $this->model = App::getModel($this::getName());
    }

    public function __set($key, $value)
    {
        $this->view->$key = $value;
    }

    /**
     * @return string
     * @route /
     */
    public function index()
    {
        $this->renderResponse(404);
    }

    protected function renderResponse($view = null)
    {
        $this->response->ViewResponse(
            $this->view->setView($view, self::getName())
        );
    }

    /**
     * @route /404
     */

    public function notFound()
    {
        $this->renderResponse(404);
    }

    protected function renderXmlResponse($data)
    {
        $this->response->XmlResponse($data);
    }
}
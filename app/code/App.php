<?php

namespace App\Code;

class App extends Object
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var AccessLayer
     */
    protected $accessLevel;

    protected $config;

    protected $controllersDirectory;

    protected $modelsDirectory;

    protected $viewsDirectory;

    public function __construct(
        $controllersDirectory = null,
        $modelDirectory = null,
        $viewsDirectory = null,
        $config = array())
    {
        $this->controllersDirectory = $controllersDirectory;

        $this->viewsDirectory = $viewsDirectory;

        $this->modelsDirectory = $modelDirectory;

        $this->config = $config;
    }

    public static function run(
        $controllersDirectory,
        $modelDirectory,
        $viewsDirectory,
        $config)
    {
        $instance = parent::getInstance(
            $controllersDirectory,
            $modelDirectory,
            $viewsDirectory,
            $config);
        $instance->setUp();
    }

    public static function getModel($model)
    {
        try {
            $reflection = new \ReflectionClass(ModelsNameSpace . $model);

            return $reflection->newInstance();
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    public static function getTableFieldObject()
    {
    }

    protected function setUp()
    {
        $this->accessLevel = AccessLayer::getInstance();
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
        $this->router = Router::getInstance();
        $this->router->scanRoutes($this->controllersDirectory);
        View::getInstance($this->viewsDirectory);
        Database::getInstance(
            $this->config['host'], $this->config['database'],
            $this->config['driver'], $this->config['username'],
            $this->config['password']
        );
        $this->invoke(
            $this->router->getRoute($this->request->getRequestPath())
        );
    }

    public function invoke($router = null)
    {

        if (empty($router) && !$this->router->routerHasDefault()) {
            return $this->invokeController(Controller::getClass(), "notFound", array());
        } else if ($this->router->routerHasDefault() && empty($router)) {
            return $this->invokeController(
                $this->router->getDefaultController(),
                $this->router->getDefaultMethod(),
                array());
        }

        return $this->invokeController(
            $router['controller'],
            $router['method'],
            $router['segments']
        );
    }

    protected function invokeController($controller, $action = null, $arguments = null)
    {
        try {
            $reflection = new \ReflectionClass($controller);
            $instance = $reflection->newInstance(
                Response::getInstance(),
                Request::getInstance(),
                View::getInstance()
                    ->setView($action, $reflection->getShortName())
            );

            $method = $reflection->getMethod($action);

            $description = $method->getDocComment();

            if (!$this->request->isRequestAllowed($description))
                return null;

            if (!$this->accessLevel->canAccess($description))
                return null;

            $method->invokeArgs($instance, $arguments);
        } catch (\LogicException $e) {
            echo $e->getMessage();
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
        }

        return null;
    }
}
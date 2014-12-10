<?php

namespace App\Code;

class App extends Object
{

    /**
     * @var Response
     */
    protected $response;

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

    const Controllers = 'App\\Code\\Controllers\\';

    const Models = 'App\\Code\\Models\\';

    const Fields = 'App\\Code\\Fields\\';

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

    public static function getController($controller)
    {
    }

    public static function getModel($model)
    {
        return App::getObjectInstance(self::Models . $model);
    }

    public static function getTableField($field)
    {
        return App::getObjectInstance(self::Fields . $field);
    }

    protected function setUp()
    {
        $this->accessLevel = AccessLayer::getInstance();
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
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
        if (($reflection = $this->getReflectionClass($controller)) == null) {
            $this->response->ViewResponse(null, 404);

            return null;
        }

        $method = $reflection->getMethod($action);

        $description = $method->getDocComment();

        if (!$this->request->isRequestAllowed($description)) {
            $this->response->ViewResponse(null, 405);

            return null;
        }

        if (!$this->accessLevel->canAccess($description)) {
            $this->response->ViewResponse(null, 403);

            return null;
        }

        try {
            $instance = $this->getObjectInstance($reflection, array(
                Response::getInstance(),
                Request::getInstance(),
                View::getInstance()
                    ->setView($action, $reflection->getShortName())
            ));

            $method->invokeArgs($instance, $arguments);
        } catch (\Exception $e) {
            $this->response->ViewResponse($e->getMessage(), 500);
            return null;
        }


        return $this;
    }

    /**
     * @param $object
     * @return null|\ReflectionClass
     */
    public static function getReflectionClass($object)
    {
        try {
            return new \ReflectionClass($object);
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * @param null $object
     * @param array $args
     * @return null|object
     */
    public static function getObjectInstance($object = null, $args = array())
    {
        if ($object instanceof \ReflectionClass)
            return $object->newInstanceArgs($args);

        try {
            $reflection = self::getReflectionClass($object);

            return $reflection->newInstanceArgs($args);
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}
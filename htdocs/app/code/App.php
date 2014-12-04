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

    protected $config;

    protected $controllersDirectory;

    protected $viewsDirectory;

    public function __construct(
        $controllersDirectory = null,
        $viewsDirectory = null,
        $config = array())
    {
        $this->controllersDirectory = $controllersDirectory;

        $this->viewsDirectory = $viewsDirectory;

        $this->config = $config;
    }

    protected function setUp()
    {
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
        //@todo handle / requests
        if (empty($router))
            return $this->invokeController(Controller::getClass(), "index", array());

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

            $reflection->getMethod($action)
                ->invokeArgs($instance, $arguments);
        } catch (\LogicException $e) {
            echo $e->getMessage();
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public static function run($controllersDirectory, $viewsDirectory, $config)
    {
        $instance = parent::getInstance($controllersDirectory, $viewsDirectory, $config);
        $instance->setUp();
    }

    public static function getMedia()
    {
        return Request::getInstance()->getRelativeUrl() . 'media' . DS;
    }

    public static function getCss($filename = null)
    {
        if(empty($filename))
            return null;

        return self::getMedia() . 'css' . DS . $filename;
    }

    public static function getJs()
    {
        if(empty($filename))
            return null;

        return self::getMedia() . 'js' . DS . $filename;
    }
} 
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

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
        $this->router->scanRoutes(AppControllers);

        $this->invoke(
            $this->router->getRoute($this->request->getRequestPath())
        );
    }

    public function invoke($router = null)
    {

        //@todo handle / requests
        if (empty($router))
            return $this->invokeController(Controller::getClass(), "notFound", array());

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
            $instance = $reflection->newInstance();
            $reflection->getMethod($action)
                ->invokeArgs($instance, $arguments);
        } catch (\LogicException $e) {
            echo $e->getMessage();
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public static function run()
    {
        return parent::getInstance();
    }
} 
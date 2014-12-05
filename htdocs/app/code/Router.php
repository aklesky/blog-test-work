<?php

namespace App\Code;

class Router extends Object
{

    protected $routeList;

    /**
     * @param $path
     * @return bool|null
     */
    public function getRoute($path)
    {
        if ($this->getRoutes() == null)
            return null;
        foreach ($this->getRoutes() as $controller) {
            if (($match = $this->match($path, $controller)) !== false) {
                return $match;
            }
        }

        return null;
    }

    public function getRoutes()
    {
        return $this->routeList;
    }

    protected function match($path, $controller)
    {
        foreach ($controller['routes'] as $action) {
            if (preg_match("#^{$action['pattern']}(?:\/?)(?:\?(.*)+?)?$#", $path, $match)) {
                unset($match[0]);
                $action['segments'] = $match;

                return $action;
            }
        }

        return false;
    }

    public function scanRoutes($directoryToScan)
    {
        if (!is_readable($directoryToScan) && !is_dir($directoryToScan))
            return false;

        $directoryIterator = new \DirectoryIterator($directoryToScan);
        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isDot())
                continue;
            if (($controller = $this->_getControllerRoutes(
                    $fileInfo->getBasename(".php"))) != null
            ) {
                $this->routeList[] = $controller;
            }
        }

        return count($this->routeList) > 0;
    }

    private function _getControllerRoutes($controllerFileName = null)
    {
        try {
            $controllerClass = new \ReflectionClass(ControllersNameSpace . $controllerFileName);

            $routeName = array(
                'name' => $controllerClass->getShortName()
            );

            $controllerName = $controllerClass->getName();

            /**
             * @var $method \ReflectionMethod
             */
            $controllerRoute = $this->_getRoutePattern($controllerClass);

            foreach ($controllerClass->getMethods() as $method) {

                if ($this->_isMethodAllowed($method)) {
                    $routeName['routes'][] = array(
                        'controller' => $controllerName,
                        'pattern' => $this->_getRoutePattern($method, $controllerRoute),
                        'method' => $method->getName(),
                    );
                }
            }

            return $routeName;
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    private function _getRoutePattern(\Reflector $reflector, $base = null)
    {
        $pattern = $this->_getRoutePatternBlock($reflector->getDocComment());

        $routePattern = !empty($pattern) ? $pattern :
            mb_strtolower($reflector->getShortName());

        return !empty($base) ? $base . DIRECTORY_SEPARATOR . $routePattern
            : $routePattern;
    }

    private function _getRoutePatternBlock($commentBlock = null)
    {
        if (preg_match('/@route\s+(.*)\r?\n/im', $commentBlock, $matches)) {
            return trim($matches[1], '/');
        }

        return null;
    }

    private function _isMethodAllowed(\ReflectionMethod $method)
    {
        return $method->isPublic() && !$method->isStatic()
        && $method->isUserDefined() && !$method->isConstructor();
    }
} 
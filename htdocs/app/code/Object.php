<?php


namespace App\Code;

abstract class Object extends \SplObjectStorage
{
    /**
     * @param $key
     * @param $request
     * @return null
     *
     * get a key from $request array
     */

    protected function getData($key, $request)
    {
        if (isset($this->$request)) {
            $data = $this->$request;

            return !empty($data[$key]) ? $data[$key] : null;
        }

        return null;
    }

    /**
     * merge $data and $arrayName arrays
     *
     * @param $data
     * @param $arrayName
     * @return $this
     */
    protected function setArrayData($data, $arrayName)
    {
        if (isset($this->$arrayName)) {
            $this->$arrayName = array_merge($this->$arrayName, $data);
        }

        return $this;
    }

    static public function getInstance()
    {
        static $instance;

        $class = self::getClass();

        if (!($instance instanceof $class)) {
            $reflection = new \ReflectionClass($class);
            $instance = $reflection->newInstanceArgs(func_get_args());
        }

        return $instance;
    }

    static public function getClass()
    {
        return get_called_class();
    }

    static public function getName()
    {
        $array = explode('\\', get_called_class());

        return end($array);
    }

    public static function capitalsToUnderscore($string = null)
    {
        return mb_strtolower(
            preg_replace('/\B([A-Z])/', '_$1', $string)
        );
    }
} 
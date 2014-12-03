<?php


namespace App\Code;


class Object extends \SplObjectStorage
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
        if(isset($this->$request)) {
            $data = $this->$request;
            return !empty($data[$key]) ? $data[$key] : null;
        }
        return null;
    }

    /**
     * merge $data and $arrayName arrays
     * @param $data
     * @param $arrayName
     * @return $this
     */
    protected function setData($data, $arrayName)
    {
        if(isset($this->$arrayName)) {
            $this->$arrayName = array_merge($this->$arrayName, $data);
        }
        return $this;
    }

    static public function getInstance()
    {
        static $instance;
        $class = get_called_class();

        if(!($instance instanceof $class)) {
            $reflection = new \ReflectionClass($class);
            $instance = $reflection->newInstanceArgs(func_get_args());
        }
        return $instance;
    }
} 
<?php

namespace App\Code;


class Request
{
    private $_headers = array();

    private $_query = array();

    private  $_post = array();

    private  $_allowedMethods = array(
        'get',
        'post',
        'put',
        'delete'
    );

    public function __construct()
    {
        $this->setPost($_POST)
            ->setQuery($_GET)
            ->getAllHeaders();
    }

    /**
     * @return string|null
     */
    public function getRequestMethod()
    {
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        if($this->getRequestMethod() == null)
            return false;

        return in_array(
            $this->getRequestMethod(), $this->_allowedMethods
        );
    }

    public function getPost($key = null)
    {
        return isset($key) ? $this->_getData($key,'_post') : $this->_post;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setPost($data = array())
    {
        return $this->_setData($data,'_post');
    }

    public function getQuery($key = null)
    {
        return isset($key) ? $this->_getData($key,'_query') : $this->_query;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setQuery($data = array())
    {
        return $this->_setData($data,'_query');
    }

    /**
     * @param $key
     * @param $request
     * @return null
     *
     * get a key from $request array
     */

    private function _getData($key, $request)
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
    private function _setData($data, $arrayName)
    {
        if(isset($this->$arrayName)) {
            $this->$arrayName = array_merge($this->$arrayName, $data);
        }
        return $this;
    }
    /**
     * @return array
     */
    public function getAllHeaders()
    {
        $headers = array();
        if(function_exists("getallheaders")) {
            $headers = array_merge($headers, getallheaders());
        } else if(function_exists("apache_request_headers")){
            $headers = array_merge($headers, apache_request_headers());
        } else if(function_exists("headers_list")){
            $headers = array_merge($headers, headers_list());
        }
        return $this->_headers = array_merge($headers, $_SERVER);
    }

    /**
     * @param null $key
     * @return bool
     */
    public function isHeaderExists($key = null)
    {
        return isset($key) ? array_key_exists($key,$this->_headers) : false;
    }

    /**
     * @param null $key
     * @return null
     */
    public function getHeader($key = null)
    {
        return $this->isHeaderExists($key) ? $this->_headers[$key] : null;
    }

    /**
     * @return bool
     */
    public function isAjaxRequest()
    {
        return $this->isHeaderExists('HTTP_X_REQUESTED_WITH')
        && mb_strtolower($this->getHeader('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }
} 
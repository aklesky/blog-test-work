<?php

namespace App\Code;

class Request extends Object
{

    protected $_headers = array();

    protected $_query = array();

    protected $_post = array();

    protected $_allowedMethods = array(
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
        return mb_strtolower($this->getHeader('REQUEST_METHOD'));
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        if ($this->getRequestMethod() == null)
            return false;

        return in_array(
            $this->getRequestMethod(), $this->_allowedMethods
        );
    }

    public function getPost($key = null)
    {
        return isset($key) ? $this->getData($key, '_post') : $this->_post;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setPost($data = array())
    {
        return $this->setData($data, '_post');
    }

    public function getQuery($key = null)
    {
        return isset($key) ? $this->getData($key, '_query') : $this->_query;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setQuery($data = array())
    {
        return $this->setData($data, '_query');
    }

    /**
     * @return array
     */
    public function getAllHeaders()
    {
        $headers = array();
        if (function_exists("getallheaders")) {
            $headers = array_merge($headers, getallheaders());
        } else if (function_exists("apache_request_headers")) {
            $headers = array_merge($headers, apache_request_headers());
        } else if (function_exists("headers_list")) {
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
        return isset($key) ? array_key_exists($key, $this->_headers) : false;
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

    public function getRelativeUrl()
    {
        return '//' . $this->getHeader('HTTP_HOST') . DS .
        ltrim($this->getBasePath(), DS);
    }

    public function getRequestPath()
    {
        $requestUri = explode('/',
            $this->clearPath($this->getHeader('REQUEST_URI'))
        );
        $scriptName = explode('/', $this->getBasePath());

        return implode('/', array_diff_assoc($requestUri, $scriptName));
    }

    protected function getBasePath()
    {
        return $this->clearPath(dirname($this->getHeader('SCRIPT_NAME')));
    }

    protected function clearPath($path = null)
    {
        return preg_replace('#(?<=/)[.+]+(?=/)#', null,
            preg_replace('#(/+){2,}#', '/',
                trim($path, "/\\")
            )
        );
    }
}

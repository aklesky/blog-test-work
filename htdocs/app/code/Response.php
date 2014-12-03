<?php

namespace App\Code;

class Response extends Object
{

    private $_headers = array();

    private $_statusArray = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        301 => 'Moved Permanently',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    );

    private $_status;

    /**
     * @param      $name
     * @param null $value
     * @param null $code
     * @param bool $replace
     *
     * @return $this
     */
    function setHeader($name, $value = null, $code = null, $replace = false)
    {
        if (!isset($value) && isset($this->_headers[$name])) {
            unset($this->_headers[$name]);
        }
        $this->_headers[$name] = array(
            "value" => $value, "code" => $code, "replace" => $replace
        );

        return $this;
    }

    /**
     * headers alredy sent ?
     *
     * @return bool
     * @throws \Exception
     */
    function isHeaderSent()
    {
        if (headers_sent($file, $line)) {
            throw new \Exception("Headers alredy sent in :" . $file . ", line :" . $line, E_WARNING);
        }

        return false;
    }

    /**
     * clear all header or header by key
     *
     * @param string $headerKey
     * @return Response
     */
    public function clearHeaders($headerKey = null)
    {
        if (!empty($this->_headers[$headerKey])) {
            unset($this->_headers[$headerKey]);
            header_remove($headerKey);

            return $this;
        }
        $this->_headers = array();

        return $this;
    }

    /**
     * set respone code;
     *
     * @param $codeNumber
     * @return Response
     */
    function setStatus($codeNumber)
    {
        if (!empty($this->_statusArray[$codeNumber])) {
            $this->_status = (int)$codeNumber;
        }

        return $this;
    }

    /**
     * print response headers
     *
     * @return Response
     */
    function printHeaders()
    {
        if (!empty($this->_status)) {
            header((php_sapi_name() == 'cgi' ? 'Status:' : 'HTTP/1.0') . ' ' . $this->_status . ' ' . $this->_statusArray[$this->_status]);
        }
        if (!$this->isHeaderSent() && !empty($this->_headers)) {
            foreach ($this->_headers as $key => $value) {
                $key = ucfirst(strtolower($key));
                if (is_null($value['code'])) {
                    header($key . ': ' . $value['value'], $value['replace']);
                } else {
                    header($key . ': ' . $value['value'], $value['replace'], $value['code']);
                }
            }
        }

        return $this;
    }

    /**
     * print headers and json response
     */
    public function JsonResponse($data = null, $headerCode = 200)
    {
        $this->clearHeaders()
            ->setHeader("Cache-Control", "no-cache, must-revalidate")
            ->setHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT")
            ->setHeader("Content-Type", "application/json")
            ->setStatus($headerCode)
            ->printHeaders();

        echo !empty($data) ? json_encode($data) : json_encode(array());

        return $this;
    }

    /**
     * @param string $data
     * @param int $headerCode
     * @return Response
     */
    public function ViewResponse($data = null, $headerCode = 200)
    {
        $this->clearHeaders()
            ->setHeader("Cache-Control", "no-cache, must-revalidate")
            ->setHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT")
            ->setHeader("Content-Type", "text/html;charset=utf-8")
            ->setStatus($headerCode)
            ->printHeaders();
        echo $data;

        return $this;
    }

    public function Redirect($location = null)
    {
        if (empty($location))
            return $this;
        $this->clearHeaders()
            ->setHeader("Location", $location)
            ->printHeaders();
        exit();
    }
}
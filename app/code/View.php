<?php

namespace App\Code;

class View extends Object
{

    protected $varsCollection = array();

    protected $autoRender = true;

    protected $content = null;

    protected $metaTags = array();

    protected $layout = 'layout';

    protected $viewDirectory = null;

    protected $defaultExtension = '.phtml';

    protected $view = null;

    protected $canonicalUrl = null;

    public function __construct($viewsDirectory = null, $layoutFile = null)
    {

        if (!empty($viewsDirectory)) {
            $this->setViewDirectory($viewsDirectory);
        }
        if (!empty($layoutFile)) {
            $this->setLayoutFile($layoutFile);
        }
    }

    public function setViewDirectory($viewsDirectory = null)
    {
        if (!is_dir($viewsDirectory) && is_readable($viewsDirectory))
            return false;

        $this->viewDirectory = rtrim($viewsDirectory . DS);

        return $this;
    }

    public function setLayoutFile($layout = null)
    {
        if (!is_readable($layout))
            return false;

        $this->layout = $this->viewDirectory . $layout . $this->defaultExtension;

        return $this;
    }

    public function __get($key)
    {
        return $this->varsCollection[$key];
    }

    public function __set($key, $var)
    {
        $this->varsCollection[$key] = $var;
    }

    public function setView($view = null, $directory = null)
    {
        if (empty($view))
            return $this;

        $view = preg_replace("/{$this->defaultExtension}$/", '', $view);

        $directory = !empty($directory) ? mb_strtolower($directory) . DS : null;

        $this->view = $this->viewDirectory .
            $directory . $view .
            $this->defaultExtension;

        return $this;
    }

    public function loadCustomViewFile($view = null, $directory = null)
    {

        $directory = !empty($directory) ? mb_strtolower($directory) . DS : null;

        $filename = $this->viewDirectory .
            $directory . $view;

        return $this->loadTemplateFile($filename);
    }

    protected function loadTemplateFile($filename)
    {
        if (!is_readable($filename))
            return null;

        ob_start();
        include $filename;
        $content = ob_get_contents();
        ob_get_clean();

        return (string)$content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->content = $this->loadTemplateFile($this->view);
        $content = $this->loadTemplateFile(
            $this->viewDirectory . $this->layout . $this->defaultExtension
        );

        return $content;
    }

    /**
     * @return null
     */
    public function getCanonicalUrl()
    {
        return $this->canonicalUrl;
    }

    /**
     * @param null $canonicalUrl
     */
    public function setCanonicalUrl($canonicalUrl)
    {
        $this->canonicalUrl = $canonicalUrl;
    }

    public static function getCurrentUrl()
    {
        return Request::getInstance()->getCurrentUrl();
    }

    public static function getUrl($path)
    {
        return Request::getInstance()->getUrl($path);
    }

    public static function getCss($filename = null)
    {
        if (empty($filename))
            return null;

        return self::getMedia() . 'css' . DS . $filename;
    }

    public static function getMedia()
    {
        return Request::getInstance()->getRelativeUrl() . 'media' . DS;
    }

    public static function getJs($filename = null)
    {
        if (empty($filename))
            return null;

        return self::getMedia() . 'js' . DS . $filename;
    }

    public static function getUpload($filename)
    {
//        if (!is_readable(Uploads . $filename))
//            return null;

        return Request::getInstance()->getRelativeUrl() . 'uploads' . DS .
        $filename;
    }
}
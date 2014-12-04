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

    public function __construct($viewsDirectory = null, $layoutFile = null)
    {

        if (!empty($viewsDirectory)) {
            $this->setViewDirectory($viewsDirectory);
        }
        if (!empty($layoutFile)) {
            $this->setLayoutFile($layoutFile);
        }
    }

    public function __set($key, $var)
    {
        $this->varsCollection[$key] = $var;
    }

    public function setLayoutFile($layout = null)
    {
        if (!is_readable($layout))
            return false;

        $this->layout = $this->viewDirectory . $layout . $this->defaultExtension;

        return $this;
    }

    public function setViewDirectory($viewsDirectory = null)
    {
        if (!is_dir($viewsDirectory) && is_readable($viewsDirectory))
            return false;

        $this->viewDirectory = rtrim($viewsDirectory . DS);

        return $this;
    }

    public function setView($view = null, $directory = null)
    {
        if (empty($view))
            return $this;

        $fileName = $this->viewDirectory .
            (!empty($directory) ? $directory . DS : null) .
            trim($view, $this->defaultExtension) .
            $this->defaultExtension;

        $this->content = $this->loadTemplateFile($fileName);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->loadTemplateFile(
            $this->viewDirectory . $this->layout . $this->defaultExtension
        );
    }

    protected function loadTemplateFile($filename)
    {
        if (!is_readable($filename))
            return null;

        ob_start();
        extract($this->varsCollection);
        include $filename;
        $content = ob_get_contents();
        ob_get_clean();

        return (string)$content;
    }
}
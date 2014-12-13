<?php

namespace php\testsuites\unit\app\code;

use App\Code\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \App\Code\Request */
    protected static $request;

    public static function setUpBeforeClass()
    {
        self::$request = Request::getInstance();
    }

    public static function tearDownAfterClass()
    {
        self::$request = null;
    }

    public function testFail()
    {
        $httpHost = 'custom.domain';
        $baseHost = 'http://' . $httpHost . DIRECTORY_SEPARATOR;
        $htdocs = '/blog-test-work/htdocs/';
        $baseUrl = $baseHost . ltrim($htdocs, DIRECTORY_SEPARATOR);

        $_SERVER['HTTP_HOST'] = $httpHost;
        $_SERVER['REQUEST_URI'] = $htdocs . 'blog/1';
        $_SERVER['SCRIPT_NAME'] = $htdocs . 'index.php';

        self::$request->getAllHeaders();

        $this->assertEquals(self::$request->getBaseHost(), $baseHost);
        $this->assertEquals(self::$request->getBasePath(), trim($htdocs, DIRECTORY_SEPARATOR));
        $this->assertEquals(self::$request->getBaseUrl(), $baseUrl);
        $this->assertEquals(self::$request->getUrl('/blog/1'), $baseUrl . 'blog/1');
    }

    public function testFail2()
    {

        $httpHost = 'blog.local';
        $baseHost = 'http://' . $httpHost . DIRECTORY_SEPARATOR;
        $htdocs = '';
        $baseUrl = $baseHost . ltrim($htdocs, DIRECTORY_SEPARATOR);

        $_SERVER['HTTP_HOST'] = $httpHost;
        $_SERVER['REQUEST_URI'] = $htdocs . '//blog/1';
        $_SERVER['SCRIPT_NAME'] = $htdocs . '/index.php';

        self::$request->getAllHeaders();

        $this->assertEquals(self::$request->getBaseHost(), 'http://blog.local/');
        $this->assertEquals(self::$request->getRequestPath(), 'blog/1');
        $this->assertEmpty(self::$request->getBasePath());
        $this->assertEquals(self::$request->getBaseUrl(), $baseUrl);
        $this->assertEquals(self::$request->getUrl('/blog/1'), $baseUrl . 'blog/1');
    }
}

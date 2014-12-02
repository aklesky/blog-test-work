<?php

use App\Code\Request;
use App\Code\Uri;

class UriTest extends PHPUnit_Framework_TestCase
{
    protected $object;
    public function setUp()
    {
        $_SERVER['REQUEST_URI'] = "https://localhost////////blog-service/htdocs//////";
        $request = new Request();
        $this->object = new Uri($request->getHeader('REQUEST_URI'));
    }

    public function testUriClass()
    {

        $this->assertInstanceOf('\App\Code\Object', $this->object,
            'Uri object is not inherited from Object class');

        $property = new ReflectionProperty($this->object, 'requestUri');
        $this->assertTrue($property->isProtected(), 'Property isn\'t a protected');

        $method = new ReflectionMethod($this->object,'getHost');
        $this->assertTrue($method->isPublic(),'getHost isn\'t a public method');

        $method = new ReflectionMethod($this->object,'getPath');
        $this->assertTrue($method->isPublic(),'getPath isn\'t a public method');

        $method = new ReflectionMethod($this->object,'getScheme');
        $this->assertTrue($method->isPublic(),'getScheme isn\'t a public method');
    }

    public function testGetterMethods()
    {
        $this->assertNotEmpty($this->object->getHost(),'getHost returns an empty value');
        $this->assertEquals('localhost', $this->object->getHost(),
            'getHost isn\'t equal to value');

        $this->assertNotEmpty($this->object->getPath(),'getPath returns an empty value');
        $this->assertEquals('blog-service/htdocs', $this->object->getPath(),
            'getPath isn\'t equal to value');

        $this->assertNotEmpty($this->object->getScheme(),'getScheme returns an empty value');
        $this->assertEquals('https', $this->object->getScheme(),
            'getScheme isn\'t equal to value');
    }
} 
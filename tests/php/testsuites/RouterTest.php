<?php

use App\Code\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Router();
    }

    public function testRouterClass()
    {
        $this->assertInstanceOf('App\Code\Object', $this->object,
            'instance is not inherited from the Object class');

        $property = new ReflectionProperty($this->object,'routeList');
        $this->assertTrue($property->isProtected(),'Property is not a protected.');

        $method = new ReflectionMethod($this->object,'match');
        $this->assertTrue($method->isProtected(), 'match isn\t a protected method');

        $method = new ReflectionMethod($this->object,'getRoute');
        $this->assertTrue($method->isPublic(), 'getRoute isn\t a public method');

    }
} 
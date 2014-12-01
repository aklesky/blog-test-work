<?php

use App\Code\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var App\Code\Request
     */
    protected $object;

    public function setUp()
    {
        $_POST['test1'] = 2;
        $_GET['query2'] = 'test3';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $this->object = new Request();
    }

    public function testIsRequiredPropertiesAreExistsInTheClass()
    {
        $property = new ReflectionProperty($this->object, '_headers');
        $this->assertTrue($property->isPrivate());
        $property = new ReflectionProperty($this->object, '_query');
        $this->assertTrue($property->isPrivate());
        $property = new ReflectionProperty($this->object, '_post');
        $this->assertTrue($property->isPrivate());
        $property = new ReflectionProperty($this->object, '_allowedMethods');
        $this->assertTrue($property->isPrivate());
    }

    public function testMethodGetRequestedMethodIsExistsAndReturnsMethod()
    {
        $method = new ReflectionMethod(
            $this->object, 'getRequestMethod');
        $this->assertTrue($method->isPublic(), 'getRequestMethod is not a public.');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNotEmpty($this->object->getRequestMethod(),
            'getRequestMethod returns a empty string');
        $this->assertEquals($this->object->getRequestMethod(),'get',
            'getRequestMethod returns a string which doesn\'t match');
    }

    public function testMethodIsAllowedExistsAndAllowsRequest()
    {
        $method = new ReflectionMethod(
            $this->object, 'isAllowed'
        );
        $this->assertTrue($method->isPublic());
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->object->isAllowed());
    }

    public function testIsGetSetPostAndQueriesMethodExistsAndReturnsPostDataByKey()
    {
        $method = new ReflectionMethod($this->object,'getPost');
        $this->assertTrue($method->isPublic(),'getPost method is not a public.');
        $method = new ReflectionMethod($this->object,'setPost');
        $this->assertTrue($method->isPublic(),'setPost method is not a public.');
        $this->assertInstanceOf('\App\Code\Request',
            $this->object->setPost(),'setPost doesn\'t returns an instance of Request class');
        $_POST['test'] = 1;
        $this->object->setPost($_POST);
        $this->assertNotEmpty($this->object->getPost(), 'getPost returns an empty value.');
        $postData = $this->object->getPost('test');
        $this->assertNotEmpty($postData, 'postData is empty.');
        $this->assertEquals($postData,1, 'postData isn\'t equals to value');
    }

    public function testIsGetSetQueryMethodExistsAndReturnsQueryByKey()
    {
        $method = new ReflectionMethod($this->object, 'getQuery');
        $this->assertTrue($method->isPublic(),'getQuery method is not a public.');

        $method = new ReflectionMethod($this->object,'setQuery');
        $this->assertTrue($method->isPublic(), 'setQuery method is not a public.');
        $this->assertInstanceOf('\App\Code\Request',
            $this->object->setQuery(), 'setQuery doesn\'t returns an instance of Request class');

        $_GET['query'] = 1;
        $this->object->setQuery($_GET);

        $this->assertNotEmpty($this->object->getQuery(), 'getQuery returns an empty value.');
        $this->assertArrayHasKey('query2',$this->object->getQuery(),
            'getQuery returns an array which doesn\'t contains a requested key');

        $queryData = $this->object->getQuery("query");
        $this->assertNotEmpty($queryData, 'getQuery returns an empty value.');
        $this->assertEquals($queryData,1, 'queryValue isn\'t equals to value');
    }

    public function testIsGetSetHeadersMethodExistsAndReturnsHeaderByKey()
    {
        $method = new ReflectionMethod($this->object, 'getAllHeaders');
        $this->assertTrue($method->isPublic(), 'getAllHeaders is not a public method.');
        $this->assertNotEmpty($this->object->getAllHeaders(), 'Request Headers are empty.');
        $this->assertArrayHasKey('SCRIPT_NAME', $this->object->getAllHeaders(),
            'headers doesn\'t contains a keys matching this name');

        $method = new ReflectionMethod($this->object, 'isHeaderExists');
        $this->assertTrue($method->isPublic(),'isHeaderExists is not a public method');
        $this->assertTrue($this->object->isHeaderExists('SCRIPT_NAME'),
            'Header with this key isn\'t exists');

        $method = new ReflectionMethod($this->object, 'getHeader');
        $this->assertTrue($method->isPublic(),'getHeader is not a public method');
        $this->assertNotEmpty($this->object->getHeader('SCRIPT_NAME'),
            'getHeader method returns an empty value');
    }

    public function testIsAjaxRequestMethod()
    {
        $method = new ReflectionMethod($this->object,'isAjaxRequest');
        $this->assertTrue($method->isPublic(),'isAjaxRequest is not a public method');
        $this->assertTrue($this->object->isAjaxRequest(),
            'This Request isn\'t a XMLHttpRequest');
    }
}
<?php

namespace Micro\ClientBundle\Tests\Rest;

use Micro\ClientBundle\Rest\Client;
use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * @runTestsInSeparateProcesses
 */
class ClientTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Client
     */
    private $client;

    /**
     *
     * @var m\Mock
     */
    private $request;
    public function setup() {
        $this->request = m::mock('alias:Httpful\Request');
        $this->client = new Client('http://test.example.com/');
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Unsupported method baz
     */
    public function throwsOnUnknownMethod() {
        $this->client->callRestfulApi('baz', '');
    }
    
    /**
     * @test
     */
    public function getSendsGetRequestWithNoParams() {
        $this->request
                ->shouldReceive('get')
                ->with('http://test.example.com/')
                ->andReturnSelf();
        $this->request
                ->shouldReceive('send')
                ->andReturn((object)['body'=>'OK']);
        $actual = $this->client->callRestfulApi('GET', '');
        $this->assertEquals('OK',$actual);
    }
    
    /**
     * @test
     */
    public function getSendsGetRequestWithParams() {
        $this->request
                ->shouldReceive('get')
                ->with('http://test.example.com/?test=baz')
                ->andReturnSelf();
        $this->request
                ->shouldReceive('send')
                ->andReturn((object)['body'=>'OK']);
        $actual = $this->client->callRestfulApi('GET', '', array('test'=>'baz'));
        $this->assertEquals('OK',$actual);
    }

    /**
     * @test
     */
    public function getSendsGetRequestWithNoParamsAndPath() {
        $this->request
                ->shouldReceive('get')
                ->with('http://test.example.com/foo/bar')
                ->andReturnSelf();
        $this->request
                ->shouldReceive('send')
                ->andReturn((object)['body'=>'OK']);
        $actual = $this->client->callRestfulApi('GET', 'foo/bar');
        $this->assertEquals('OK',$actual);
    }
    
    /**
     * @test
     */
    public function getSendsGetRequestWithParamsAndPath() {
        $this->request
                ->shouldReceive('get')
                ->with('http://test.example.com/foo/bar?test=baz&rem=boo')
                ->andReturnSelf();
        $this->request
                ->shouldReceive('send')
                ->andReturn((object)['body'=>'OK']);
        $actual = $this->client->callRestfulApi('GET', 'foo/bar',array('test'=>'baz','rem'=>'boo'));
        $this->assertEquals('OK',$actual);
    }
    
    /**
     * @test
     * @dataProvider dataMethods
     */
    public function dataMethodsSendsProperRequestWithData($method, $expectedMethod) {
        $this->request
                ->shouldReceive($expectedMethod)
                ->with('http://test.example.com/foo/bar', '{"test":"boo"}')
                ->andReturnSelf();
        $this->request
                ->shouldReceive('send')
                ->andReturn((object)['body'=>'OK']);
        $actual = $this->client->callRestfulApi($method, 'foo/bar', json_encode(['test'=>'boo']));
        $this->assertEquals('OK',$actual);
    }
    
    public function dataMethods() {
        return [
            'put' => ['PUT','put'],
            'post' => ['POST','post'],
        ];
    }
}

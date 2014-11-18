<?php

namespace Framework\Tests\Routing;


use Framework\Http\Request;
use Framework\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $router;

    protected function setUp()
    {
        parent::setUp();

        $this->router = new Router([
            '/hello' => [
                '_controller' => 'Application\\Controller\\HelloController',
                '_action' => 'hello'
            ],
            '/bye' => [
                '_controller' => 'Application\\Controller\\ByeController',
                '_action' => 'bye'
            ]
        ]);
    }

    /** @dataProvider provideRequestData */
    public function testMatch($path, $controller, $action)
    {
        $request = new Request('POST',$path ,'HTTP',"1.1");
        $this->assertSame(
            [
                '_controller' => $controller,
                '_action' => $action
            ], $this->router->match($request)
        );
    }

    public function provideRequestData()
    {
        return [
            ['/hello', 'Application\\Controller\\HelloController', 'hello'],
            ['/bye', 'Application\\Controller\\ByeController', 'bye'],
        ];
    }

    /** @expectedException \Framework\Routing\Exception\RouteNotFoundException */
    public function testRouteNotFoundException()
    {
        $request = new Request('POST','/home','HTTP',"1.1");
        $this->router->match($request);
    }
} 
<?php

namespace Framework;


use Framework\Http\Response;
use Framework\Routing\Exception\RouteNotFoundException;
use Framework\Routing\Router;

class Kernel
{
    private $router;

    function __construct(Router $router)
    {
        $this->router = $router;
    }


    /**
     *Converts a request into a response
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            $route = $this->router->match($request);
        } catch (RouteNotFoundException $e) {
            return new Response("Not Found", 404, [], "HTTP", "1.1");
        }

        $controller = new $route['_controller'];
        $method = $route['_action'] . 'Action';

        $response = call_user_func([$controller, $method], [$request]);

        if (!$response instanceof Response) {
            throw new \RuntimeException(sprintf(
                'Controller %s::%s must return a Response object.',
                get_class($controller),
                $method
            ));
        }

        return $response;
    }
} 
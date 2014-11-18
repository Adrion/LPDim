<?php

namespace Framework\Routing;


use Framework\Http\Request;
use Framework\Routing\Exception\RouteNotFoundException;

class Router
{
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function match(Request $request)
    {
        $url = $request->getPath();

        if (!isset($this->routes[$url])) {
            throw new RouteNotFoundException(sprintf(
                'No route found with this url "%s"',
                $url
            ));
        }
        return $this->routes[$url];
    }
}
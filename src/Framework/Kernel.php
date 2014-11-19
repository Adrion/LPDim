<?php
namespace Framework;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Exception\RouteNotFoundException;
use Framework\Routing\Router;
use Framework\Templating\TemplateEngine;

class Kernel
{
    private $templating;
    private $router;

    public function __construct(Router $router, TemplateEngine $templating)
    {
        $this->router = $router;
        $this->templating = $templating;
    }

    /**
     * Converts a request into a response.
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
            return $this->createResponse($request, 'Page Not Found', 404);
        } catch (\Exception $e) {
            return $this->createResponse($request, 'Internal Server Error', 500);
        }
        $controller = new $route['_controller']($this->templating);
        $method = $route['_action'].'Action';
        $response = call_user_func_array([ $controller, $method], [ $request ]);
        if (!$response instanceof Response) {
            throw new \RuntimeExeption(sprintf(
                'Controller %s::%s must return a Response object.',
                get_class($controller),
                $method
            ));
        }
        return $response;
    }
    private function createResponse(Request $request, $body, $statusCode = 200, array $headers = [])
    {
        return Response::createFromRequest($request, $body, $statusCode, $headers);
    }
}
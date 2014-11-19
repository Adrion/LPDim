<?php
require_once __DIR__.'/../vendor/autoload.php';
use Framework\Http\Request;
use Framework\Kernel;
use Framework\Routing\Router;
use Framework\Templating\TemplateEngine;
use Framework\Templating\TwigEngineAdapter;
$router = new Router([
    '/hello' => [
        '_controller' => 'Application\\Controller\\HelloController',
        '_action' => 'hello',
    ],
]);
//$templating = new TemplateEngine(__DIR__.'/../views');
$loader = new \Twig_Loader_Filesystem(__DIR__.'/../views');
$templating = new TwigEngineAdapter(new \Twig_Environment($loader));

$request = Request::createFromGlobals();

$kernel = new Kernel($router, $templating);
$response = $kernel->handle($request);
$response->send();
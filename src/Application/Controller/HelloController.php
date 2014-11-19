<?php
namespace Application\Controller;
use Framework\Http\Request;
use Framework\Templating\TemplateEngine;
class HelloController
{
    private $templating;
    public function __construct(TemplateEngine $templating)
    {
        $this->templating = $templating;
    }
    public function helloAction(Request $request)
    {
        return $this->templating->createViewResponse($request, 'hello.twig', [
            'name' => 'World',
        ]);
    }
}
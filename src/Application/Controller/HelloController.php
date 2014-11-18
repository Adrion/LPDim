<?php

namespace Application\Controller;


use Framework\Http\Request;
use Framework\Http\Response;

class HelloController
{
    public function helloAction(Request $request)
    {
        return new Response('Hello World',200,[],'HTTP','1.1');
    }
} 
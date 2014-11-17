<?php

require_once __DIR__.'/../vendor/autoload.php';

$request = new \Framework\Http\Request(
    'GET',
    '/',
    'HTTP',
    '1.1',
    [
        'Host' => 'google.com',
        'User-Agent' => 'Gobelins/DIM'
    ],
    'test'
);

header('Content-Type: text/plain');
echo $request->getMessage();
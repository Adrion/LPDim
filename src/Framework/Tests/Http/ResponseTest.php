<?php

namespace Framework\Tests\Http;

use Framework\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateHttpResponse()
    {
        $message = 'HTTP/1.1 200 OK'."\n";
        $message.= 'Content-Type: text/plain'."\n";
        $message.= 'Content-Length: 12'."\n";
        $message.= "\n";
        $message.= 'Hello World!';

        $headers = [
            'Content-Type'   => 'text/plain',
            'Content-Length' => 12,
        ];

        $response = new Response('Hello World!', 200, $headers, 'HTTP', '1.1');

        $this->assertSame('Hello World!', $response->getBody());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(2, $response->getHeaders());
        $this->assertSame('HTTP', $response->getScheme());
        $this->assertSame('1.1', $response->getVersion());
        $this->assertSame($message, $response->getMessage());
        $this->assertSame('OK', $response->getReasonPhrase());
    }

    /** @expectedException \Framework\Http\Exception\MalformedHttpMessageException */
    public function testRejectMalformedHttpMessage()
    {
        Response::createFromMessage('');
    }

    public function testCreateHttpResponseFromHttpMessage()
    {
        $message = 'HTTP/1.1 200 OK'."\n";
        $message.= 'Content-Type: text/plain'."\n";
        $message.= 'Content-Length: 12'."\n";
        $message.= "\n";
        $message.= 'Hello World!';

        $response = Response::createFromMessage($message);

        $this->assertSame('Hello World!', $response->getBody());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(2, $response->getHeaders());
        $this->assertSame('HTTP', $response->getScheme());
        $this->assertSame('1.1', $response->getVersion());
        $this->assertSame($message, $response->getMessage());
        $this->assertSame('OK', $response->getReasonPhrase());
    }
}

<?php

namespace Framework\Tests\Http;

use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateHttpRequestFromHttpMessage()
    {
        $message = 'POST /contact HTTPS/1.1'."\n";
        $message.= 'Host: www.foobar.tid'."\n";
        $message.= 'User-Agent: Firefox/Gecko'."\n";
        $message.= "\n";
        $message.= "Hello World";

        $request = Request::createFromMessage($message);

        $this->assertInstanceOf('Framework\Http\Request', $request);

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/contact', $request->getPath());
        $this->assertSame('HTTPS', $request->getScheme());
        $this->assertSame('1.1', $request->getVersion());
        $this->assertCount(2, $request->getHeaders());
        $this->assertSame($message, $request->getMessage());
        $this->assertSame("Hello World", $request->getBody());
    }

    public function testCreateHttpRequestWithHeaderAndEmptyBody()
    {
        $request = new Request('POST','/contact','HTTPS',"1.1", [
            'Host' => 'www.foobar.tid',
            'User-Agent' => 'Firefox/Gecko'
        ], "Hello World");

        $message = 'POST /contact HTTPS/1.1'."\n";
        $message.= 'Host: www.foobar.tid'."\n";
        $message.= 'User-Agent: Firefox/Gecko'."\n";
        $message.= "\n";
        $message.= "Hello World";

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/contact', $request->getPath());
        $this->assertSame('HTTPS', $request->getScheme());
        $this->assertSame('1.1', $request->getVersion());

        $this->assertCount(2, $request->getHeaders());
        $this->assertSame($message, $request->getMessage());
        $this->assertSame("Hello World", $request->getBody());
    }

    public function testCreateHttpRequest()
    {

        $request = new Request('GET','/','HTTP',"1.1");
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/', $request->getPath());
        $this->assertSame('HTTP', $request->getScheme());
        $this->assertSame('1.1', $request->getVersion());
        $this->assertEmpty($request->getHeaders());
        $this->assertEmpty($request->getBody());
        $this->assertSame('GET / HTTP/1.1', $request->getMessage());
    }
} 
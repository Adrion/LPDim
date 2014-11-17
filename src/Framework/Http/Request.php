<?php

namespace Framework\Http;

class Request
{
    private $method;
    private $path;
    private $scheme;
    private $version;
    private $headers;
    private $body;

    public function __construct($method, $path, $scheme, $version, array $headers = [], $body = '')
    {
        $this->method = $method;
        $this->path = $path;
        $this->scheme = $scheme;
        $this->version = $version;
        $this->headers = $headers;
        $this->body = $body;
    }

    public static function createFromMessage($message)
    {
        $lines = explode("\n", $message);
        $pattern = '`^(?P<method>[A-Z]+) (?P<path>\S+) (?P<scheme> HTTPS?)\/(?P<version>1\.[0-1])$`i';

        preg_match($pattern, $lines[0], $matches);

        return new self('a','a','a','a');
    }

    public function getMessage()
    {
        $message = sprintf(
            '%s %s %s/%s',
            $this->getMethod(),
            $this->getPath(),
            $this->getScheme(),
            $this->getVersion()
        );
        $message.= "\n";
        foreach ($this->getHeaders() as $name => $value) {
            $message.= sprintf('%s: %s', $name, $value);
            $message.= "\n";
        }

        $message = rtrim($message);
        if ($body = $this->getBody()) {
            $message.= "\n\n";
            $message.= $body;
        }

        return $message;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

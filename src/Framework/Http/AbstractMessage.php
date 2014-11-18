<?php

namespace Framework\Http;

abstract class AbstractMessage {
    protected  $body;
    protected  $headers;
    protected  $scheme;
    protected  $version;

    final public function getMessage()
    {
        $message = $this->createHeadersPrologue();

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

    abstract protected function createHeadersPrologue();

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
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
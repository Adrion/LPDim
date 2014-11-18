<?php

namespace Framework\Http;

use Framework\Http\Exception\MalformedHttpMessageException;

class Response extends AbstractMessage
{
    private $statusCode;

    public function __construct($body, $statusCode, array $headers, $scheme, $version)
    {
        $statusCode = (int) $statusCode;
        if ($statusCode < 100 || $statusCode > 599) {
            throw new \InvalidArgumentException('$statusCode must be a valid integer between 100 and 599.');
        }

        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->scheme = $scheme;
        $this->version = $version;
    }

    public static function createFromMessage($message)
    {
        $lines = explode("\n", $message);

        $pattern = '`^(HTTPS?)\/(1\.[0-1]) ([1-5][0-9]{2}) (.+)$`i';

        if (!preg_match($pattern, $lines[0], $matches)) {
            throw new MalformedHttpMessageException(sprintf(
                'Response line of message "%s" must match regular expression "%s".',
                $message,
                $pattern
            ));
        }

        list(, $scheme, $version, $statusCode) = $matches;

        $blankLineIndex = null;
        $nbLines = count($lines);
        $headers = [];
        for ($i = 1; $i < $nbLines; $i++) {
            if (empty($lines[$i])) {
                $blankLineIndex = $i;
                break;
            }

            list($name, $value) = explode(': ', $lines[$i]);

            $headers[$name] = $value;
        }

        $content = [];
        if ($blankLineIndex) {
            for ($i = $blankLineIndex + 1; $i < $nbLines; $i++) {
                $content[] = $lines[$i];
            }
        }

        $body = implode("\n", $content);

        return new self($body, $statusCode, $headers, $scheme, $version);
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        if (200 === $this->statusCode) {
            return 'OK';
        }

        if (201 === $this->statusCode) {
            return 'Created';
        }

        // ...
    }

    public function createHeadersPrologue()
    {
        return sprintf(
            '%s/%s %u %s',
            $this->getScheme(),
            $this->getVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
    }

}

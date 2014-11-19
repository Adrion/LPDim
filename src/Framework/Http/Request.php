<?php

namespace Framework\Http;

use Framework\Http\Exception\MalformedHttpMessageException;

class Request extends AbstractMessage
{
    /**
     * List of all RFC2616 compliant HTTP verbs.
     *
     * The list can be expanded to non standard
     * HTTP verbs such as LINK and UNLINK for
     * RESTful web services or PURGE for reverse
     * proxy caches like Varnish.
     *
     * @see Request::registerSupportedVerbs
     * @var array
     */
    private static $verbs = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'PATCH',
        'TRACE',
        'OPTIONS',
        'CONNECT',
        'PATCH',
    ];

    private $method;
    private $path;

    public function __construct($method, $path, $scheme, $version, array $headers = [], $body = '')
    {
        $this->method = $method;
        $this->path = $path;
        $this->scheme = $scheme;
        $this->version = $version;
        $this->headers = $headers;
        $this->body = $body;
    }

    public static function registerSupportedVerbs(array $verbs)
    {
        foreach ($verbs as $verb) {
            self::$verbs[] = strtoupper($verb);
        }
    }

    public static function createFromGlobals()
    {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        list($scheme,$version) = explode('/', $_SERVER['SERVER_PROTOCOL']);

        $header = [];
        foreach ($_SERVER as $key => $value) {
            if ('HTTP_' !== substr($key, 0, 5)){
                continue;
            }

            $name = substr($key, 5);              // USER_AGENT
            $name = strtolower($name);            // user_agent
            $name = str_replace('_', ' ', $name); // user agent
            $name = ucwords($name);               // User Agent
            $name = str_replace(' ', '-', $name); // User-Agent

            $header[$name] = $value;
        }

        return new self($method, $path, $scheme, $version, $header);
    }

    public static function createFromMessage($message)
    {
        $lines = explode("\n", $message);

        $pattern = '`^([A-Z]+) (\S+) (HTTPS?)\/(1\.[0-1])$`i';

        if(!preg_match($pattern, $lines[0], $matches)){
            /* \ correspond au namespace global, afin d'acceder au Exception natif de php et non une instance du namespace local*/
            throw new MalformedHttpMessageException(sprintf(
                'Request "%s" line must fetch regular expression: "%s"',
                $message,
                $pattern
            ));
        }

        list(, $method, $path, $scheme, $version) = $matches;

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

        return new self($method, $path, $scheme, $version, $headers, $body);
    }

    public function createHeadersPrologue()
    {
        return sprintf(
            '%s %s %s/%s',
            $this->getMethod(),
            $this->getPath(),
            $this->getScheme(),
            $this->getVersion()
        );
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

}

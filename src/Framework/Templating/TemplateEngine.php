<?php
namespace Framework\Templating;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Templating\Exception\TemplateNotFoundException;
class TemplateEngine
{
    private $location;
    public function __construct($location)
    {
        $this->location = $location;
    }
    public function renderView($view, array $variables = [])
    {
        $template = $this->loadTemplate($view);
        $mapping = $this->getVariablesMapping($variables);
        return str_replace(array_keys($mapping), array_values($mapping), $template);
    }
    private function getVariablesMapping(array $variables)
    {
        $mapping = [];
        foreach ($variables as $name => $value) {
            $key = sprintf('[[%s]]', $name);
            $mapping[$key] = $value;
        }
        return $mapping;
    }
    private function loadTemplate($view)
    {
        $path = $this->location.'/'.$view;
        if (!is_readable($path)) {
            throw new TemplateNotFoundException(sprintf(
                'Template %s does not exist or is not readable.',
                $path
            ));
        }
        return file_get_contents($path);
    }
    public function createViewResponse(Request $request, $view, array $variables = [])
    {
        $body = $this->renderView($view, $variables);
        return Response::createFromRequest($request, $body);
    }
}
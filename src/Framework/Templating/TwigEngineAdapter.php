<?php
namespace Framework\Templating;
class TwigEngineAdapter extends TemplateEngine
{
    private $twig;
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    public function renderView($view, array $variables = [])
    {
        return $this->twig->render($view, $variables);
    }
}
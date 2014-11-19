<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 19/11/14
 * Time: 11:34
 */

namespace Framework\Tests\Templating;


use Framework\Templating\TemplateEngine;

class TemplateEngineTest extends \PHPUnit_Framework_TestCase
{

    /** @var TemplateEngine*/
    private $engine;

    public function testRenderView()
    {
        $content = $this->engine->renderView('demo.tpl', [
            'name' => 'cedric',
            'age'  => 20
        ]);

        $this->assertSame(
            "My name is cedric, I'm 20 !" , trim($content)
        );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->engine = new TemplateEngine(__DIR__.'/../Ressources');
    }

    protected function tearDown()
    {
        $this->engine = null;

        parent::tearDown();
    }
} 
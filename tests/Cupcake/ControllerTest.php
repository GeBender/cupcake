<?php
namespace Cupcake;

use Layout\Flatlab;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class ControllerTest extends TestHelper
{


    public function setUp()
    {
        $app = new Application();
        $app['request'] = function ()
        {
            return $this->getMock('Symfony\Component\HttpFoundation\Request');
        };
        $app['Vars'] = $this->getMock('Cupcake\Vars');
        $app['Templating'] = $this->getMock('Symfony\Component\Templating\PhpEngine', array(), array(
                $this->getMock('Symfony\Component\Templating\TemplateNameParser'),
                $this->getMock('Symfony\Component\Templating\Loader\FilesystemLoader', array(), array('path'))
        ));

        $app['route'] = array(
                'layout' => false,
                'webFolder' => false,
                'view' => false,
                'extensionView' => false,
                'appName' => false,
                'controller' => false
        );
        $app['config'] = array('title' => false);

        $app['GPS'] = $this->getMock('Cupcake\GPS', array(), array($app['route'], array(), 'path'));
        self::$Class = new Controller($app);

    }


    public function testDeveSetarELerVariavelPeloCallDinamico()
    {
        $expected = 'value';
        self::$Class->app['Vars']->expects($this->exactly(2))->method('__call')->will($this->returnValue($expected));
        self::$Class->setVarDinamica($expected);

        $actual = self::$Class->getVarDinamica();

        $this->assertEquals($expected, $actual);

    }


    public function testRenderizacaoPadrao_comLayoutSemRetornoDoAction()
    {
        $expected = 'viewRenderizadaComLayout';

        self::$Class->app['GPS']->expects($this->once())->method('getLayoutClassName')->will($this->returnValue('Cupcake\Controller'));
        self::$Class->app['Templating']->expects($this->any())->method('render')->will($this->returnValue($expected));

        self::$Class->layout = true;
        $actual = self::$Class->render(null);

        $this->assertEquals($expected, $actual);

    }

    public function testRenderizacaoPeloRetornoDoAction()
    {
        $expected = 'qualquerRetorno';

        self::$Class->layout = false;
        $actual = self::$Class->render($expected);

        $this->assertEquals($expected, $actual);

    }

    public function testRenderizacaoDeViewSemLayout()
    {
        $expected = 'viewRenderizadaSemLayout';

        self::$Class->app['Templating']->expects($this->once())->method('render')->will($this->returnValue($expected));

        self::$Class->layout = false;
        $actual = self::$Class->render(null);

        $this->assertEquals($expected, $actual);

    }

    public function testComponentComClasse()
    {
        self::$Class->app['GPS']->expects($this->once())->method('getComponentClassName')->will($this->returnValue('Cupcake\Controller'));
        self::$Class->app['GPS']->expects($this->once())->method('getComponentViewFile')->will($this->returnValue(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'LICENSE.txt'));
        $actual = self::$Class->useComponent('test');

        $this->assertNotNull($actual);
    }


    public function testComponentSemClasse()
    {
        self::$Class->app['GPS']->expects($this->once())->method('getComponentClassName')->will($this->returnValue(false));
        self::$Class->app['GPS']->expects($this->once())->method('getComponentViewFile')->will($this->returnValue(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'LICENSE.txt'));
        $actual = self::$Class->useComponent('test');

        $this->assertNotNull($actual);
    }


}

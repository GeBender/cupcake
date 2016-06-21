<?php
namespace Cupcake;

class GPSTest extends TestHelper
{


    public function setUp()
    {
        $route = array(
                'layout' => false,
                'appsFolder' => false,
                'controllerFolder' => false,
                'action' => false,
                'webFolder' => false,
                'view' => false,
                'extensionView' => false,
                'appName' => false,
                'controller' => false
        );
        $config = array('title' => false);

        self::$Class = new GPS($route, $config, 'app/controller/action/arg1/arg2');
        self::$Class->fs = $this->getMock('Cupcake\Filesystem');

    }


    public function testRoute()
    {
        $actual = self::$Class->route();

        $this->assertInternalType('array', $actual);
    }


    public function testConfig()
    {
        $actual = self::$Class->config();

        $this->assertInternalType('array', $actual);
    }


    public function testAppName()
    {
        $expected = 'App';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(true));
        self::$Class->fs->expects($this->at(1))->method('exists')->will($this->returnValue(false));
        self::$Class->defineAppName();

        $actual = self::$Class->route['appName'];

        $this->assertEquals($expected, $actual);
    }

    public function testMergeConfig()
    {
        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->url[0] = 'CacambaNet';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(true));
        self::$Class->mergeConfigs();
        $expected = count(self::$Class->config);

        $this->assertGreaterThan(0, $expected);

    }

    public function testTiraParteVaziaDaUrl()
    {
        $expected = '';

        self::$Class->url = array('a');
        self::$Class->shiftUrl();

        $actual = self::$Class->url[0];

        $this->assertEquals($expected, $actual);
    }


    public function testController()
    {
        $expected = 'Controller';

        self::$Class->url = array($expected);
        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(true));
        self::$Class->defineController();

        $actual = self::$Class->route['controller'];

        $this->assertEquals($expected, $actual);
    }


    public function testAction()
    {
        $expected = 'home';

        self::$Class->url = array($expected);
        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['controllerFolder'] = 'Controller';
        self::$Class->route['controller'] = 'Index';

        self::$Class->fs->expects($this->once())->method('methodExists')->will($this->returnValue(true));

        self::$Class->defineAction();

        $actual = self::$Class->route['action'];

        $this->assertEquals($expected, $actual);
    }


    public function testGetControllerPath()
    {
        $actual = self::$Class->getControllerPath();
        $this->assertNotNull($actual);

    }


    public function testLayoutClassNameDoApp()
    {
        $expected = 'Layout\Apps\Simplesys\Layout\layout';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(true));

        $actual = self::$Class->getLayoutClassName('layout');

        $this->assertEquals($expected, $actual);

    }


    public function testLayoutClassNameDefault()
    {
        $expected = 'Layout\layout';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('classExists')->will($this->returnValue(true));

        $actual = self::$Class->getLayoutClassName('layout');

        $this->assertEquals($expected, $actual);

    }


    public function testLayoutClassNameNaotem()
    {
        $expected = false;

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('classExists')->will($this->returnValue(false));

        $actual = self::$Class->getLayoutClassName('layout');

        $this->assertEquals($expected, $actual);

    }

    public function testComponentClassNameDoApp()
    {
        $expected = 'Apps\Apps\Simplesys\Layout\Component\component';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';
        self::$Class->route['componentFolder'] = 'Component';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(true));

        $actual = self::$Class->getComponentClassName('component');

        $this->assertEquals($expected, $actual);

    }


    public function testComponentClassNameDefault()
    {
        $expected = 'Layout\Component\component';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';
        self::$Class->route['componentFolder'] = 'Component';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('classExists')->will($this->returnValue(true));

        $actual = self::$Class->getComponentClassName('component');

        $this->assertEquals($expected, $actual);

    }


    public function testComponentClassNameNaoTem()
    {
        $expected = false;

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['layoutFolder'] = 'Layout';
        self::$Class->route['componentFolder'] = 'Component';

        self::$Class->fs->expects($this->at(0))->method('classExists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('classExists')->will($this->returnValue(false));

        $actual = self::$Class->getComponentClassName('component');

        $this->assertEquals($expected, $actual);

    }


    public function testComponentViewFileDoApp()
    {
        $expected = 'Apps\Simplesys\View\Component\component.phtml';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['componentFolder'] = 'Component';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(true));

        $actual = self::$Class->getComponentViewFile('Component');

        $this->assertEquals($expected, $actual);

    }


    public function testComponentViewFileDefault()
    {
        $expected = 'View\Component\component.phtml';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['componentFolder'] = 'Component';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('exists')->will($this->returnValue(true));

        $actual = self::$Class->getComponentViewFile('Component');

        $this->assertEquals($expected, $actual);

    }


    public function testComponentViewFileNaoTem()
    {
        $expected = false;

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['componentFolder'] = 'Component';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('exists')->will($this->returnValue(false));

        $actual = self::$Class->getComponentViewFile('Component');

        $this->assertEquals($expected, $actual);

    }


    public function testLayoutViewFileDoApp()
    {
        $expected = 'Apps\Simplesys\View\layout.phtml';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['layout'] = 'layout';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(true));

        $actual = self::$Class->getLayoutViewFile();

        $this->assertEquals($expected, $actual);

    }


    public function testLayoutViewFileDefault()
    {
        $expected = 'View\layout.phtml';

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['layout'] = 'layout';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('exists')->will($this->returnValue(true));

        $actual = self::$Class->getLayoutViewFile();

        $this->assertEquals($expected, $actual);

    }


    public function testLayoutViewFileNaoTem()
    {
        $expected = false;

        self::$Class->route['appsFolder'] = 'Apps';
        self::$Class->route['appName'] = 'Simplesys';
        self::$Class->route['viewFolder'] = 'View';
        self::$Class->route['layout'] = 'layout';
        self::$Class->route['extensionView'] = 'phtml';

        self::$Class->fs->expects($this->at(0))->method('exists')->will($this->returnValue(false));
        self::$Class->fs->expects($this->at(1))->method('exists')->will($this->returnValue(false));

        $actual = self::$Class->getLayoutViewFile();

        $this->assertEquals($expected, $actual);

    }

}

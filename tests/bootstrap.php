<?php
namespace Cupcake;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'Apps/CacambaNet/Model/Cacambas.php';
require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'Apps/CacambaNet/Model/Assinantes.php';

putenv('AMBIENT=test');
define('DS', DIRECTORY_SEPARATOR);

class TestHelper extends \PHPUnit_Framework_TestCase
{

    public $dados = array();

    public static $Class;
    public static $App;

    public static function setUpBeforeClass()
    {
    	$app = new Application();
    	self::$App = $app;
    }

    public function setUp()
    {
    	self::$App['request'] = function () {
    		return $this->getMock('Request');
    	};

    	self::$App['route'] = array('layout' => false);

    	/*
        Simplifica::$arquivo = $this->getMock('arquivo');
        Simplifica::$viewer = $this->getMock('viewer');

        Simplifica::$config = array(
        'appPadrao' => 'SimplificaTest',
        'controllerPadrao' => 'indexCTest',
        'actionPadrao' => 'indexATest',
        'layoutPadrao' => 'charisma',
        'folder' => 'folderTest'
                );

        Simplifica::$server = array(
        'HTTP_HOST' => 'simplifica.com.br',
        'REQUEST_URI' => '/'
                );
                */

    }

}

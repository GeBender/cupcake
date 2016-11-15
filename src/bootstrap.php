<?php
/**
 * (c) CupcakePHP: The Rapid and Tasty Development Framework.
 *
 * PHP version 5.5.12
 *
 * @author  Ge Bender <gesianbender@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version GIT: <git_id>
 * @link    http://cupcake.simplesys.com.br
 */
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', true);

define('DS', DIRECTORY_SEPARATOR);
$autoload = require_once('vendor' . DS . 'autoload.php');

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$cupcake = new Application();
$cupcake['debug'] = true;

if ((bool) strstr($_SERVER['HTTP_HOST'], 'homolog') === true) {
    putenv('AMBIENT=homolog');
} elseif ((bool) strstr($_SERVER['HTTP_HOST'], '.dev') === true) {
    putenv('AMBIENT=development');
}

if (getenv('AMBIENT') === false) {
    putenv('AMBIENT=production');
}

if ($cupcake['debug'] === true) {
    ini_set('display_errors', true);
}

$cupcake['autoload'] = $autoload;
$cupcake['Vars'] = new Cupcake\Vars();
$cupcake['FileSystem'] = new Cupcake\Filesystem();
$cupcake['config'] = function () {
    return Cupcake\Config::load();
};

$cupcake['route'] = function () {
    return Cupcake\Config::route();
};

require_once('vendor/cupcake/src/Debug.php');
Debug::setCupcaker($cupcake['debug']);

$cupcake->match('{url}', function (Request $request) use ($cupcake) {
    $cupcake['GPS'] = new Cupcake\GPS($cupcake['route'], $cupcake['config'], substr($request->getPathInfo(), 1));
    $cupcake['route'] = $cupcake['GPS']->route();
    $cupcake['config'] = $cupcake['GPS']->config();
    $cupcake['request'] = $request;
    $cupcake['Auth'] = new Cupcake\Auth();

    $cupcake['autoload']->add('', dirname(dirname(dirname(__DIR__))) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['modelFolder'] . DS);

    $config = Setup::createAnnotationMetadataConfiguration(
    		array(dirname(dirname(__FILE__)) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['modelFolder']),
    		$cupcake['debug']
    );

    if (isset($cupcake['config']['db']) === true) {
        $conn = parse_url($cupcake['config']['db']);
        $conn['driver'] = str_replace('pdo-mysql', 'pdo_mysql', $conn['scheme']);
        $conn['dbname'] = substr($conn['path'], 1);
        $conn['driverOptions'] = array(
                1002 => 'SET NAMES utf8'
        );
        if (isset($conn['pass']) === true) {
            $conn['password'] = $conn['pass'];
        }
        $cupcake['db'] = EntityManager::create($conn, $config);
    } else {
        $cupcake['db'] = [];
    }

    $config->addCustomStringFunction('REPLACE', 'Cupcake\DoctrineComplements\ReplaceFunction');

//     var_dump(dirname(dirname(dirname(__DIR__))) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['viewFolder'] . DS . '%name%',
//              dirname(dirname(dirname(__DIR__))) . DS . $cupcake['route']['layoutFolder'] . '/%name%',
//              dirname(dirname(__FILE__)) . '/Layout/'.$cupcake['route']['layout'].'/View/%name%',
//              dirname(dirname(__FILE__)) . '/Layout/%name%',
//              dirname(__FILE__).'/Apps/Cupcake/View/%name%');
    $loader = new FilesystemLoader(array(
        dirname(dirname(dirname(__DIR__))) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['viewFolder'] . DS . '%name%',
        dirname(dirname(dirname(__DIR__))) . DS . $cupcake['route']['layoutFolder'] . '/%name%',
        dirname(dirname(__FILE__)) . '/Layout/'.$cupcake['route']['layout'].'/View/%name%',
        dirname(dirname(__FILE__)) . '/Layout/%name%',
        dirname(__FILE__).'/Apps/Cupcake/View/%name%',
    ));
    $templateNameParser = new TemplateNameParser();
    $cupcake['Templating'] = new PhpEngine($templateNameParser, $loader);

    $controllerPath = $cupcake['route']['controller'];
    $controller = new $controllerPath($cupcake);
    $action = $cupcake['route']['action'];

    $respostAction = $controller->$action();
    $toRender = $controller->render($respostAction);

    if (is_object($cupcake['db']) === true) {
        $cupcake['db']->close();
    }
    return $toRender;

})->assert('url', '.+|');

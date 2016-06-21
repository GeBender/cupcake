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
}

if (getenv('AMBIENT') === false) {
    putenv('AMBIENT=production');
    $cupcake['debug'] = false;
	ini_set('display_errors', false);
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

$cupcake->match('{url}', function (Request $request) use ($cupcake) {
    $cupcake['GPS'] = new Cupcake\GPS($cupcake['route'], $cupcake['config'], substr($request->getPathInfo(), 1));
    $cupcake['route'] = $cupcake['GPS']->route();
    $cupcake['config'] = $cupcake['GPS']->config();
    $cupcake['request'] = $request;
    $cupcake['Auth'] = new Cupcake\Auth();

    $cupcake['autoload']->add('', dirname(dirname(__FILE__)) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['modelFolder'] . DS);

    $config = Setup::createAnnotationMetadataConfiguration(
    		array(dirname(dirname(__FILE__)) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['modelFolder']),
    		$cupcake['debug'],
    		dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tmp'
    );
    $conn = parse_url($cupcake['config']['db']);
    $conn['driver'] = str_replace('pdo-mysql', 'pdo_mysql', $conn['scheme']);
    $conn['dbname'] = substr($conn['path'], 1);
    $conn['driverOptions'] = array(
            1002 => 'SET NAMES utf8'
    );
    if (isset($conn['pass']) === true) {
        $conn['password'] = $conn['pass'];
    }

    $config->addCustomStringFunction('REPLACE', 'Cupcake\DoctrineComplements\ReplaceFunction');

    $cupcake['db'] = EntityManager::create($conn, $config);
    $controllerPath = $cupcake['route']['controller'];

    $controller = new $controllerPath($cupcake);
    $action = $cupcake['route']['action'];

    $loader = new FilesystemLoader(array(
            dirname(dirname(__FILE__)) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['viewFolder'] . DS . '%name%',
            dirname(dirname(__FILE__)) . DS . $cupcake['route']['layoutFolder'] . DS . '%name%',
            dirname(dirname(__FILE__)) . DS . $cupcake['route']['appsFolder'] . DS . $cupcake['route']['appName'] . DS . $cupcake['route']['layoutFolder'] . DS . '%name%',
    ));
    $templateNameParser = new TemplateNameParser();
    $cupcake['Templating'] = new PhpEngine($templateNameParser, $loader);

    $respostAction = $controller->$action();
    $toRender = $controller->render($respostAction);

    $cupcake['db']->close();
    return $toRender;

})->assert('url', '.+|');

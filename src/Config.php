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
namespace Cupcake;

use Apps;

class Config
{


    /**
     * Entrega as configurações globais do projeto
     *
     * @return array
     */
    public static function load()
    {
        $ambientConfigs = array(
                'default' => array(
                        'title' => 'CupcakePHP: The Rapid and Tasty Development Framework.',
                        'nomeSistema' => 'CupcakePHP'
                ),
                'production' => array(
                        'db' => str_replace('pdo_mysql', 'pdo-mysql', 'pdo-'.getenv('CLEARDB_DATABASE_URL'))
                ),
                'homolog' => array(),
                'test' => array(),
                'development' => array(
                        'db' => 'pdo-mysql://root@localhost/cupcake'
                )
        );

         $AppConfig = self::getAppConfig();
         $config = array_merge(
            $ambientConfigs['default'],
            (isset($ambientConfigs[getenv('AMBIENT')]) === true) ? $ambientConfigs[getenv('AMBIENT')] : [],
            (isset($AppConfig['default']) === true) ? $AppConfig['default'] : [],
            (isset($AppConfig[getenv('AMBIENT')]) === true) ? $AppConfig[getenv('AMBIENT')] : []
        );

        return $config;

    }


    /**
     * Entrega as configurações de roteamento do projeto
     *
     * @return array
     */
    public static function route()
    {
        $AppConfig = self::getAppConfig();
        $appConfigRoute = (isset($AppConfig['route']) === true) ? $AppConfig['route'] : [];

        return array_merge(array(
                'appName' => 'Cupcake',
                'entity' => '',
                'controller' => 'Cupcake\Controller',
                'action' => 'welcomeToCupcake',
                'arguments' => array(),
                'appsFolder' => 'Apps',
                'controllerFolder' => 'Controller',
                'viewFolder' => 'View',
                'layoutFolder' => 'Layout',
                'modelFolder' => 'Model',
                'componentFolder' => 'Component',
                'webFolder' => 'Web',
                'layout' => 'flatlab',
                'extensionView' => 'phtml',
                'view' => 'Index' . DS . 'home',
                'twig' => true,
                'contentVar' => 'content',
                'assetFolder' => 'assets',
                'cupcakeFolder' => 'vendor/cupcake',
                'uploadsFolder' => 'uploads',
        ), $appConfigRoute);

    }


    public static function getAppConfig()
    {
        if(class_exists('Apps\Config') === true) {
            $AppsConfig = Apps\Config::load();
            $appConfigClass = 'Apps\\' . $AppsConfig['route']['defaultApp'] . '\\Config';

            return $appConfigClass::load();
        }
        return [];
    }
}

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
namespace Cupcake\helpers;

use Cupcake\Helper;
use Softr\Asaas\Adapter\BuzzAdapter;
use Softr\Asaas\Asaas;

class AsaasDriver extends \Cupcake\Helper
{

    public $driver;


    public function __construct($app, $routeEntity=false)
    {
        parent::__construct($app, $routeEntity);

        $adapter = $adapter = new BuzzAdapter($app['config']['AsaaS']);
        $this->driver = new Asaas($adapter);


        $cliente = $this->driver->payment()->getByCustomer(276715);
        dbg($cliente, true);

    }
}
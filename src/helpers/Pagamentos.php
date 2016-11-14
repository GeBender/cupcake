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
use Cupcake\helpers\Pagamentos\PagamentoModel;

class Pagamentos extends \Cupcake\Helper
{

    public $driver;

    public function __construct($app, $routeEntity=false)
    {
        parent::__construct($app, $routeEntity);

        $driverClass = '\Cupcake\helpers\Pagamentos\\' . $app['config']['pagamento']['gateway'];
        $this->driver = new $driverClass($app);

    }

    public function newPagamento($Pagamento)
    {
        if ($Pagamento) {
            return $this->driver->newPagamento($Pagamento);
        }
        return false;

    }

    public function getLastOpenedPayment($id) {
        $Pagamento = $this->driver->getLastOpenedPayment($id);
        return $this->newPagamento($Pagamento);
    }

    public function getClient($id) {
        return $this->driver->getClient($id);
    }

    public function getPagamentosDoCliente($id) {
        $Pagamentos = $this->driver->getPagamentosDoCliente($id);

        $pagamentos = [];
        foreach ($Pagamentos as $Pagamento) {
            $pagamentos[] = $this->newPagamento($Pagamento);
        }

        return $pagamentos;
    }

    /**
     * @param \Assinantes $Assinante
     */
    public function createClient($Assinante)
    {
        $this->uses('Patios');
        $Endereco = $this->PatiosDAO->getPatioPadraoDoAssinante($Assinante->getId());
        return $this->driver->createClient($Assinante, $Endereco);
    }


    /**
     * @param \Assinantes $Assinante
     */
    public function updateSubscription($Assinante)
    {
        return $this->driver->updateSubscription($Assinante);
    }
}
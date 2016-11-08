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

    public function newPagamento($PagamentoAsaas)
    {
        $Pagamento = new PagamentoModel();

        if ($PagamentoAsaas) {
            $Pagamento->setId($PagamentoAsaas->invoiceNumber);
            $Pagamento->setNossoNumero($PagamentoAsaas->nossoNumero);
            $Pagamento->setClienteId($PagamentoAsaas->customer);
            $Pagamento->setValor($PagamentoAsaas->value);
            $Pagamento->setValor($PagamentoAsaas->value);
            $Pagamento->setVencimento($PagamentoAsaas->dueDate);
            $Pagamento->setStatus($this->driver->getStatus($PagamentoAsaas->status));
            $Pagamento->setLinkPagamento($PagamentoAsaas->invoiceUrl);
            $Pagamento->setBoletoPagamento($PagamentoAsaas->boletoUrl);

            return $Pagamento;
        }
        return false;

    }

    public function getLastOpenedPayment($id) {
        $PagamentoAsaas = $this->driver->getLastOpenedPayment($id);
        return $this->newPagamento($PagamentoAsaas);
    }

    public function getPagamentosDoCliente($id) {
        $PagamentosAsaas = $this->driver->getPagamentosDoCliente($id);

        $pagamentos = [];
        foreach ($PagamentosAsaas as $PagamentoAsaas) {
            $pagamentos[] = $this->newPagamento($PagamentoAsaas);
        }

        return $pagamentos;
    }
}
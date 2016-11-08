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
namespace Cupcake\helpers\Pagamentos;

use Softr\Asaas\Asaas as AsaasDriver;
use Softr\Asaas\Adapter\BuzzAdapter;
use Cupcake\helpers\Pagamentos\PagamentoModel;

class Asaas implements PagamentosInterface
{

    public $driver;

    public function __construct($app)
    {
        $adapter = new BuzzAdapter($app['config']['pagamento']['key']);
        $this->driver = new AsaasDriver($adapter);
    }

    public function getLastOpenedPayment($id)
    {
        $clientes = $this->driver->customer()->getAll(['limit' => 100]);
        $payments = array_reverse($this->driver->payment()->getByCustomer($id));
        foreach ($payments as $payment) {
            if ($payment->status === 'PENDING' || $payment->status === 'OVERDUE') {
                return $payment;
            }
        }
    }

    public function getPagamentosDoCliente($id)
    {
        return $this->driver->payment()->getByCustomer($id);
    }

    public function getClient($id)
    {
        return $this->driver->customer()->getById($id);
    }

    public function getStatus($status)
    {
        $statusPagamento = [
            'PENDING' => PagamentoModel::AGUARDANDO,
            'CONFIRMED' => PagamentoModel::CONFIRMADO,
            'RECEIVED' => PagamentoModel::RECEBIDO,
            'OVERDUE' => PagamentoModel::ATRASADO
        ];

        return $statusPagamento[$status];
    }

}
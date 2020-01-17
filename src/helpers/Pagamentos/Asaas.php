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

    public function getPaymentStatus($status)
    {
        $statusPagamento = [
            'PENDING' => PagamentoModel::AGUARDANDO,
            'CONFIRMED' => PagamentoModel::CONFIRMADO,
            'RECEIVED' => PagamentoModel::RECEBIDO,
            'OVERDUE' => PagamentoModel::ATRASADO
        ];

        return $statusPagamento[$status];
    }

    public function getSubscriptionCycle($periodo)
    {
        $cycles = [
            'mensal' => 'MONTHLY',
            'trimestral' => 'QUARTERLY',
            'semestral' => 'SEMIANNUALLY',
            'anual' => 'YEARLY'
        ];

        return $cycles[$periodo];
    }

    public function getSubscriptionBillingType($formaDePagamento)
    {
        return ($formaDePagamento === 'credito') ? 'CREDIT_CARD' : 'UNDEFINED';
    }


    public function newPagamento($PagamentoAsaas)
    {
        $Pagamento = new PagamentoModel();

        $Pagamento->setId($PagamentoAsaas->invoiceNumber);
        $Pagamento->setNossoNumero($PagamentoAsaas->nossoNumero);
        $Pagamento->setClienteId($PagamentoAsaas->customer);
        $Pagamento->setValor($PagamentoAsaas->value);
        $Pagamento->setValor($PagamentoAsaas->value);
        $Pagamento->setVencimento($PagamentoAsaas->dueDate);
        $Pagamento->setStatus($this->getPaymentStatus($PagamentoAsaas->status));
        $Pagamento->setLinkPagamento($PagamentoAsaas->invoiceUrl);
        $Pagamento->setBoletoPagamento($PagamentoAsaas->boletoUrl);
        $Pagamento->setOriginal($PagamentoAsaas);

        return $Pagamento;
    }

    /**
     * @param \Assinantes $dadosCliente
     * @param \Enderecos $Endereco
     */
    public function createClient($Assinante, $Endereco)
    {
        try {
        $clientData = [
            "name" => $Assinante->getRazao(),
            "email" => $Assinante->getEmail(),
            "mobilePhone" => $Assinante->getCelular(),
            "cpfCnpj" => $Assinante->getDocClean(),
            "postalCode" => $Endereco->getCep(),
            "address" => $Endereco->getEndereco(),
            "addressNumber" => $Endereco->getNumero(),
            "complement" => $Endereco->getComplemento(),
            "province" => $Endereco->getBairro(),
        ];

        return $this->driver->customer()->create($clientData);
        } catch (\Softr\Asaas\Exception\HttpException $e) {
            dbg($e, true);
        }
    }

    public function updateSubscription($Assinante) {
        try {
            $subscriptionData = [
                "customer" => $Assinante->getIdGatewayPagamento(),
                "description" => $Assinante->getDescricaoAssinatura(),
                "billingType" => $this->getSubscriptionBillingType($Assinante->getFormaDePagamento()),
                "nextDueDate" => $Assinante->getProximoVencimento(),
                "value" => $Assinante->getValor(),
                "status" => 'ACTIVE',
                "cycle" => $this->getSubscriptionCycle($Assinante->getPeriodo())
            ];

            if($subscription = $this->driver->subscription()->getByCustomer($Assinante->getIdGatewayPagamento())) {
                $this->cancelPendingPayments($Assinante);
                $subscription = $this->driver->subscription()->delete($subscription[0]->id);
            }
            $subscription = $this->driver->subscription()->create($subscriptionData);
            return $subscription;
        } catch (\Softr\Asaas\Exception\HttpException $e) {
            dbg($e, true);
        }

    }

    public function cancelPendingPayments($Assinante)
    {
        $payments = $this->getPagamentosDoCliente($Assinante->getIdGatewayPagamento());
        foreach ($payments as $payment) {
            if ($payment->status === 'PENDING') {
                $this->driver->payment()->delete($payment->id);
            }
        }
    }

}
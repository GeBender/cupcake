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

interface PagamentosInterface
{


    public function getLastOpenedPayment($id);

    public function getPagamentosDoCliente($id);

    public function getPaymentStatus($status);

    public function getClient($id);

    public function newPagamento($pagamento);

    public function createClient($Assinante, $Endereco);

    public function updateSubscription($Assinante);

}
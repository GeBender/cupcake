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

use Cupcake\Model;

class PagamentoModel extends Model
{

    public $id;
    public $nossoNumero;
    public $clienteId;
    public $valor;
    public $vencimento;
    public $pagamento;
    public $status;
    public $dataPagamento;
    public $linkPagamento;
    public $linkBoleto;

    const AGUARDANDO = 'Aguardando pagamento';
    const CONFIRMADO = 'Confirmado';
    const RECEBIDO = 'Recebido';
    const ATRASADO = 'Atrasado';


    public function getVencimento($format)
    {
        return $this->getData('vencimento', $format);
    }

    public function getLabelStatus()
    {
        $labels = [
            self::AGUARDANDO => 'lightgray',
            self::CONFIRMADO => 'info',
            self::RECEBIDO => 'success',
            self::ATRASADO => 'danger'
        ];

        return $labels[$this->getStatus()];
    }

    public function getColorStatus()
    {
        $labels = [
            self::AGUARDANDO => '#797979',
            self::CONFIRMADO => '#797979',
            self::RECEBIDO => '#797979',
            self::ATRASADO => 'red'
        ];

        return $labels[$this->getStatus()];
    }
    public function getIconStatus()
    {
        $labels = [
            self::AGUARDANDO => 'clock-o',
            self::CONFIRMADO => 'thumbs-o-up',
            self::RECEBIDO => 'check',
            self::ATRASADO => 'exclamation-triangle'
        ];

        return $labels[$this->getStatus()];
    }

    public function estaPago()
    {
        if ($this->getStatus() === self::CONFIRMADO || $this->getStatus() === self::RECEBIDO) {
            return true;
        }
    }

    public function estaEmAberto()
    {
        if ($this->getStatus() === self::ATRASADO || $this->getStatus() === self::AGUARDANDO) {
            return true;
        }
    }
}
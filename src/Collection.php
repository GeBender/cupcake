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

class Collection extends Model
{

    public $order;

    public $direction;

    public $group;

    public $collection = array();

    public $agrupado = false;

    public $args = array();


    public function __construct($collection)
    {
        $this->collection = $collection;
    }


    public function args($args)
    {
        $this->args = $args;
        return $this;
    }


    public function count()
    {
        $i = 0;
        foreach ($this->collection as $item) {
            if (is_object($item) === true) {
                $i++;
            }
        }

        return $i;
    }


    public function somaGrupo($itens, $campo)
    {
        $getter = 'get' . ucfirst($campo);
        $total = 0;

        foreach ($itens as $each) {
            $total += $each->$getter();
        }

        return $total;
    }


    public function soma($campo)
    {
        $getter = 'get' . ucfirst($campo);
        $total = 0;
        foreach ($this->collection as $each) {
            if ($this->agrupado === true) {
                foreach ($each as $each2) {
                    $total += $each2->$getter();
                }
            } else {
                $total += $each->$getter();
            }
        }

        return $total;
    }


    public function ordena($controle)
    {
        (isset($_GET['order']) === true) ? $order = $_GET['order'] : $order = $controle;
        (isset($_GET['direction']) === true) ? $direction = $_GET['direction'] : $direction = 'ASC';

        $orderOptions = $this->indexaLista($order);
        ($direction === 'ASC') ? ksort($orderOptions) : krsort($orderOptions);
        $lista = array();
        foreach ($orderOptions as $itens) {
            $lista = array_merge($lista, $itens);
        }

        $this->collection = $lista;
    }


    public function indexaLista($campo)
    {
        $orderOptions = array();
        foreach ($this->collection as $item) {
            $orderOptions[$this->getIndexField($item, $campo)][] = $item;
        }

        return $orderOptions;
    }


    public function getIndexField($item, $campo)
    {
        $getter = 'get' . $campo;
        if ($item->$campo instanceof \DateTime) {
            return $item->$getter('Ymd');
        } else if (is_object($item->$getter()) === true) {
            return $item->$getter()->getId();
        }

        return $item->$getter();
    }


    public function agrupa()
    {
        if (isset($_GET['group']) === false) {
            return false;
        } else {
            $agrupado = $this->indexaLista($_GET['group']);
        }

        $lista = array();
        foreach ($agrupado as $k => $itens) {
            $lista[] = $this->formatGroup($k);
            $lista = array_merge($lista, $itens);
        }

        $this->collection = $lista;
    }
}

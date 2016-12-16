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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @MappedSuperclass
 */
abstract class Model
{

    const ORDER = 'id';

    const DIRECTION = 'ASC';

    const LIMIT = 50;

    const OFFSET = 0;

    const GROUP = false;

    protected $id;

    protected $listSeparator = ', ';

    protected $icon = 'fa fa-star-o';

    protected $saida = 'lista';

    protected $labels = [];

    protected $plural;

    protected $singular;

    protected $genero = 'o';

    protected $identifier = 'id';

    protected $internalFields = [
            'listSeparator',
            'icon',
            'saida',
            'labels',
            'plural',
            'singular',
            'genero',
            'identifier',
            'internalFields',
            'extraInternalFields',
            'lista'
    ];

    protected $lista = [];

    protected $extraInternalFields = [];

    /**
     * Call dinamico para invocar getters e setters pelo fw
     *
     * @param string $name
     * @param array $arguments
     *
     * @return string
     */
    public function __call($name, $arguments)
    {
        $param = $this->defineCallParam($name);
        $prefix = substr($name, 0, 3);

        if ($prefix === 'has') {
            return isset($this->$param);
        } elseif ($prefix === 'set') {
            $this->$param = $arguments[0];
            return true;
        } elseif ($prefix === 'sum') {
            $this->$param += $arguments[0];
            return true;
        } elseif ($prefix === 'cct') {
            $this->$param .= $arguments[0];
            return true;
        } elseif ($prefix === 'add') {
            if (($this->$param instanceof \Doctrine\Common\Collections\ArrayCollection) === false) {
                $this->$param = new ArrayCollection();
            }

            return $this->$param->add($arguments[0]);
        } elseif ($prefix === 'fnd') {
            $getter = 'get' . $param;
            if ((bool) $this->$getter()) {
                $manyToManies = $this->$getter()->toArray();
                foreach ($manyToManies as $manyToMany) {
                    if ($manyToMany->getId() === $arguments[0]) {
                        return true;
                    }
                }
            }
            return false;
        } elseif (property_exists($this, $param) === true && $prefix !== 'add') {
            return $this->$param;
        } elseif (isset($this->$name) === true) {
            return $this->$name;
        }//end if

        $stacktrace = debug_backtrace();
        die('Método de Model não encontrado: <b>'.$name . '</b> em ' . $stacktrace[0]['file'] . '#' . $stacktrace[0]['line']);
    }


    public function getId()
    {
        return $this->id;
    }


    public function get($campo)
    {
        $getter = 'get' . ucfirst($campo);
        return $this->$getter();
    }


    public function defineCallParam($name)
    {
        $name = lcfirst(substr($name, 3));

        $vars = get_object_vars($this);
        foreach ($vars as $k => $v) {
            if ((bool) strchr($k, '_') === true) {
                $camelize = explode('_', $k);
                $camel = '';
                foreach ($camelize as $part) {
                    $camel .= ucfirst($part);
                }
                if (lcfirst($camel) === $name) {
                    $name = $k;
                }
            }
        }

        return $name;
    }


    public function getIcon()
    {
        return $this->icon;
    }


    public function udata($data)
    {
        if ($data instanceof \DateTime) {
            $dataHora = explode(' ', $data->format('Y-m-d H:i:s'));
            $data = explode('-', $dataHora[0]);
            $hora = explode(':', $dataHora[1]);

            return mktime($hora[0], $hora[1], $hora[2], $data[1], $data[2], $data[0]);
        }
    }


    public function setData($data)
    {
        if ((bool) $data === true) {
            $data = $this->checkAndConvertDataToDate($data);
            return new \DateTime($data);
        }
    }


    public function checkAndConvertDataToDate($data)
    {
        if (strchr($data, '/')) {
            $data = explode('/', $data);
            return $data[2].'-'.$data[1].'-'.$data[0];
        }
        return $data;
    }


    public function getData($campo, $format)
    {
        if ($this->$campo instanceof \DateTime) {
            return $this->$campo->format($format);
        }

        return false;
    }


    public function ptbrToFloat($valor)
    {
        return (float) str_replace(',', '.', str_replace('.', '', $valor));
    }


    public function getObject($param, $class)
    {
        if (is_object($this->$param) === false) {
            $this->$param = new $class();
        }

        return $this->$param;
    }


    public function validaCnpj($cnpj)
    {
        $cnpj = preg_replace('/(\.|\(|\)|\/|\-|_| )+/', '', $cnpj);
        if (strlen($cnpj) != 14 || ! is_numeric($cnpj)) {
            return false;
        }

        switch ($cnpj) {
            case "00000000000000":
            case "11111111111111":
            case "22222222222222":
            case "33333333333333":
            case "44444444444444":
            case "55555555555555":
            case "66666666666666":
            case "77777777777777":
            case "88888888888888":
            case "99999999999999":
                return false;
        }

        $k = 6;
        $soma1 = "";
        $soma2 = "";
        for ($i=0; $i<13; $i++) {
            $k = $k == 1 ? 9 : $k;
            $soma2 += ($cnpj {$i} * $k);
            $k --;
            if ($i < 12) {
                if ($k == 1) {
                    $k = 9;
                    $soma1 += ($cnpj {$i} * $k);
                    $k = 1;
                } else {
                    $soma1 += ($cnpj {$i} * $k);
                }
            }
        }

        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

        return ( $cnpj[12] == $digito1 && $cnpj[13] == $digito2 );
    }


    public function getLabel($coluna)
    {
        return (isset($this->labels[$coluna]) === true) ? $this->labels[$coluna] : ucfirst($coluna);
    }

    public function getPlural()
    {
        if ((bool) $this->plural === true) {
            return $this->plural;
        }

        return get_class($this);
    }


    public function getSingular()
    {
        if ((bool) $this->singular === true) {
            return $this->singular;
        }

        return get_class($this);
    }


    public function getColunasDaLista()
    {
        if (count($this->lista) === 0) {
            $internalFields = array_merge($this->internalFields, $this->extraInternalFields);
            $fields = array_keys(get_class_vars(get_class($this)));
        } else {
            return $this->lista;
        }

        return array_diff($fields, $internalFields);
    }

    public function getOrder()
    {
        return self::ORDER;
    }
}

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

use \Cupcake\NoInjection as NoInjection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Cupcake\Cookies;

class Lista extends \Cupcake\Helper
{

    public $filters = array();

    public $order;

    public $direction;

    public $limit;

    public $group;

    public $offset;

    public $paginator;

    public $navigateQtd = 5;


    public function __construct($app, $entity = false)
    {
        parent::__construct($app, $entity);
        $this->setDefaults();

    }


    public function setDefaults()
    {
        $model = $this->model;

        (isset($this->request['order']) === true) ? $this->setOrder($this->request['order']) : $this->setOrder($model::ORDER);
        (isset($this->request['direction']) === true) ? $this->setDirection($this->request['direction']) : $this->setDirection($model::DIRECTION);
        (isset($this->request['limit']) === true) ? $this->setLimit($this->request['limit']) : $this->setLimit($model::LIMIT);
        (isset($this->request['offset']) === true) ? $this->setOffset($this->request['offset']) : $this->setOffset($model::OFFSET);
        (isset($this->request['group']) === true) ? $this->setGroup($this->request['group']) : $this->setGroup($model::GROUP);

    }


    public function get(array $criteria = array(), array $joins = array())
    {
        $qb = $this->app['db']->createQueryBuilder()
            ->select($this->entity)
            ->from($this->entity, $this->entity)
            ->where($this->mountWhere())
            ->setFirstResult($this->offset)
            ->setMaxResults($this->limit)
            ->orderBy($this->entity . '.' . $this->order, $this->direction);

        foreach ($joins as $k => $v) {
            $qb->leftJoin($k, $v);
        }

        if (count($criteria) > 0) {
            foreach ($criteria as $and) {
                $qb->andWhere($and);
            }
        }

        $qb = $qb->getQuery();
        $this->paginator = new Paginator($qb, true);
        $lista = $qb->getResult();
        $this->useComponent('HeaderLista');
        $this->useComponent('Pagination');

        $this->help('Pages');

        return $lista;

    }


    public function mountWhere()
    {
        $where = '(1 = 1)';
        $model = $this->model;

        if (property_exists($model, 'assinante') === true) {
            $this->request['search'][] = $this->entity . '.assinante = ' . $this->app['Auth']->assinanteId();
        }

        if (isset($this->request['search']) === true) {
            foreach ($this->request['search'] as $k => $v) {
                $where .= ' AND (' . $v . ')';
            }
        }

        return $where;

    }


    public function getLinkOrder($campo)
    {
        $bkp = $_GET;
        $inverse = array(
                'DESC' => 'ASC',
                'ASC' => 'DESC'
        );

        $_GET['order'] = $campo;
        ($campo === $this->order) ? $_GET['direction'] = $inverse[$this->direction] : $_GET['direction'] = 'ASC';
        $link = $this->getHere() . $this->paramsToUrl();
        $_GET = $bkp;

        return $link;

    }


    public function getLinkGroup($campo)
    {
        $bkp = $_GET;

        if (@$_GET['group'] === $campo) {
            unset($_GET['group']);
        } else {
            $_GET['group'] = $campo;
        }

        $link = $this->getHere() . $this->paramsToUrl();
        $_GET = $bkp;

        return $link;

    }


    public function getLinkOffset($offset)
    {
        $bkp = $_GET;
        $_GET['offset'] = (($offset - 1) * $this->limit);
        $link = $this->getHere() . $this->paramsToUrl();
        $_GET = $bkp;

        return $link;

    }


    public function getLinkLimit($limit)
    {
        $bkp = $_GET;
        $_GET['limit'] = $limit;
        $link = $this->getHere() . $this->paramsToUrl();
        $_GET = $bkp;

        return $link;

    }


    public function paramsToUrl()
    {
        $paramsUrl = '';
        if (count($_GET) > 0) {
            $paramsUrl = '?';
        }

        $i = 0;
        foreach ($_GET as $k => $param) {
            $paramsUrl .= $k . '=' . $param;
            if ($i < (count($_GET) - 1)) {
                $paramsUrl .= '&';
            }

            $i++;
        }

        return $paramsUrl;

    }


    public function getIconOrder($campo)
    {
        $asc = 'sort-alpha-desc blue';
        $desc = 'sort-alpha-asc blue';
        $false = 'sort gray';

        $inverse = array(
                'DESC' => $asc,
                'ASC' => $desc
        );

        ($campo === $this->order && isset($_GET['direction']) === true) ? $iconDirection = $inverse[$_GET['direction']] : $iconDirection = $asc;
        ($campo === $this->order) ? $retorna = $iconDirection : $retorna = $false;

        return 'fa fa-' . $retorna;

    }


    public function getIconGroup($campo)
    {
        $false = 'magnet gray';
        $true = 'magnet fa-flip-vertical red';
        ($campo === $this->group) ? $retorna = $true : $retorna = $false;

        return 'fa fa-' . $retorna;

    }


    public function getTotal()
    {
//        return false;
         return $this->paginator->count();

    }


    public function getPages()
    {
        return ceil(($this->getTotal() / $this->limit));

    }


    public function getPage()
    {
        $page = ceil(($this->offset + 1) / $this->limit);
        return $page;

    }


    public function getPaginateMargem()
    {
        return floor(($this->navigateQtd / 2));

    }


    public function getPageIni()
    {
        $ini = (($this->getPage() - $this->getPaginateMargem()));
        return ($ini > 1) ? $ini : 1;

    }


    public function getPaginateIni()
    {
        $ini = $this->getPageIni();

        $tamanho = $this->getPaginateTamanho();
        $corrigeTamanho = ($this->navigateQtd - $tamanho);
        $TamanhoCorrigido = ($ini - $corrigeTamanho);

        return ($TamanhoCorrigido > 1) ? $TamanhoCorrigido : 1;

    }


    public function getPageEnd()
    {
        $end = ($this->getPage() + $this->getPaginateMargem());
        if ($end < $this->navigateQtd) {
            $end = $this->navigateQtd;
        }

        ($end < $this->getPages()) ? $return = $end : $return = $this->getPages();

        return $return;

    }

    public function getTituloColuna($coluna) {
    	return $this->model->getLabel($coluna);
    }


    public function getPaginateTamanho()
    {
        return ($this->getPageEnd() - (($this->getPageIni() - 1)));

    }


    public function getPaginateEnd()
    {
        $end = ($this->getPage() + $this->getPaginateMargem());
        if ($end < $this->navigateQtd) {
            $end = $this->navigateQtd;
        }

        return ($end < $this->getPages()) ? $end : $this->getPages();

    }


    public function setOrder($order)
    {
        $this->order = NoInjection::sql($order);

    }


    public function setDirection($direction)
    {
        $this->direction = NoInjection::sql($direction);

    }


    public function setLimit($limit)
    {
        $this->limit = NoInjection::sql($limit);

    }


    public function setOffset($offset)
    {
        $this->offset = NoInjection::sql($offset);

    }


    public function setGroup($group)
    {
        $this->group = NoInjection::sql($group);

    }


}

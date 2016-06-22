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

use \Assinantes;
use Cupcake\Collection;

class DAO
{

    public $db;

    public $app;

    public $EntityRepository;

    public $ClassMetadata;

    public $name;

    public $query;


    public function __construct($app, $entity = '')
    {
        $this->app = $app;
        $this->defineName($app, $entity);
        $this->db = $app['db'];

        if ($app['FileSystem']->classExists($this->name) === true) {
            $this->EntityRepository = $app['db']->getRepository($this->name);
            $this->ClassMetadata = $this->db->getMetadataFactory()
                ->getMetadataFor($this->name);
        }

    }


    public function listar()
    {
        $this->query = $this->createQueryBuilder()
            ->select($this->name);
        return $this;

    }


    public function defineName($app, $entity)
    {
        $classParts = explode('\\', get_class($this));
        $class = array_pop($classParts);
        $class = str_replace('DAO', '', $class);
        if ($class !== '') {
            $this->name = $class;
        } else if ($entity !== '') {
            $this->name = $entity;
        } else {
            $this->name = $app['route']['entity'];
        }

    }


    /**
     * Encontra ou carrega o model
     * @param array $data
     * @return Model
     */
    public function findModel($data)
    {
        $model = $this->EntityRepository->find($data[$this->name]['id']);
        if ($model === null) {
            $model = $this->loadModel($data[$this->name]['id']);
        }

        return $model;

    }


    public function listen(array $data)
    {
        $model = $this->findModel($data);
        $mappings = $this->ClassMetadata->getAssociationMappings();
        foreach ($data[$this->name] as $k => $v) {
            $set = 'set' . ucfirst($k);
            if (isset($mappings[$k]) === true) {
                $mappedDAO = new DAO($this->app, $mappings[$k]['targetEntity']);
                // Antes fazia isso se o v não fosse array: $mappedDAO->find($v)

                $key = each($v);
                if (is_array($v) === true && (bool) $key['value'] === true) {
                	$v = $mappedDAO->listen(array($mappings[$k]['targetEntity'] => $v));
                } else if (is_object($v) === false) {
                     $v = null;
                }

                $model->$set($v);
            } else {
                (is_array($v) === true) ? $v = implode($model->getListSeparator(), $v) : false;
                $method = 'set' . ucfirst($k);
                $model->$method($v);
            }
        }

        return $model;

    }


    public function defineAssinante($model)
    {
    	if (property_exists($model, 'assinante') === true) {
        	if ((bool) $model->getAssinante() === false) {
	            $AssinantesDAO = new DAO($this->app, 'Assinantes');
	            $model->setAssinante($AssinantesDAO->find($this->app['Auth']->assinanteId()));
        	}
        }

        return $model;

    }


    public function loadModel()
    {
        $name = $this->name;
        $model = new $name();

        return $model;

    }


    public function find($id)
    {
        $criteria = array(
                'id' => $id
        );
        $result = $this->findBy($criteria);

        if (isset($result[0]) === true) {
            return $result[0];
        }

    }


    public function defineFiltroAssinante(array $criteria)
    {
        $modelName = $this->name;
        $model = new $modelName();
        if (property_exists($model, 'assinante') === true && ((bool) $this->app['Auth']->assinanteId() === true || (bool) $model->getAssinante() === true)) {
            $assinanteId = ((bool) $model->getAssinante() === true) ? $model->getAssinante() : $this->app['Auth']->assinanteId();
        	$criteria['assinante'] = (string) $assinanteId;
        }

        return $criteria;

    }


    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria = $this->defineFiltroAssinante($criteria);
        $result = $this->EntityRepository->findBy($criteria, $orderBy, $limit, $offset);

        return $result;

    }


    public function findAll(array $criteria = array())
    {
        return $this->findBy($criteria);

    }


    public function findDisponiveis($field = 'status', $flag = 'Disponível')
    {
        return $this->findBy(array(
                $field => $flag
        ));

    }


    public function salvar($model)
    {
    	$original = $this->find($model->getId());
        if ($original === null) {
            $model->setId($this->getNext('id'));
            $this->db->persist($model);
            $model = $this->defineAssinante($model);
        }

        $this->db->merge($model);
        $this->db->flush();

        return $model;

    }


    public function deletar($id)
    {
        $model = $this->find($id);
        $this->db->remove($model);
        $this->db->flush();

        return true;

    }


    public function createQueryBuilder()
    {
        $modelName = $this->name;
        $model = new $modelName();

        $query = $this->db->createQueryBuilder()
            ->select($modelName)
            ->from($modelName, $modelName);

        if (property_exists($model, 'assinante') === true) {
            $query->where($modelName . '.assinante = ' . $this->app['Auth']->assinanteId());
        }

        return $query;

    }


    public function getNext($field)
    {
         $modelName = $this->name;

        $next = $this->createQueryBuilder()
            ->select('(MAX(' . $modelName . '.' . $field . ') + 1) AS next')
            ->getQuery()
            ->getResult();

        if ($next[0]['next'] === null) {
            $next[0]['next'] = 1;
        }

        return $next[0]['next'];

    }


    public function ordena($order, $direction)
    {
        if (isset($_GET['order']) === true && isset($_GET['direction']) === true) {
            $this->query->addOrderBy($ordens[$_GET['order']], $_GET['direction']);
        } else {
            $this->query->addOrderBy($order, $direction);
        }

        return $this;

    }


    public function getResult()
    {
        return $this->query
            ->getQuery()
            ->getResult();

    }


    public function run()
    {
        $lista = $this->getResult();

        (class_exists($this->name . 'Collection') === true) ? $collectionClassName = $this->name . 'Collection' : $collectionClassName = 'Collection';
        $collection = new $collectionClassName($lista);

        return $collection;

    }


}
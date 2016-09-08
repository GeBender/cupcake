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

use \Cupcake\Flash as Flash;
use Apps\Simplesys\Controller\IndexController;
use Symfony\Component\Debug\header;

class Controller
{

    public $app;

    public $db;

    public $entity;

    public $DAO;

    public $layout = null;

    public $view = null;

    public $args = array();

    public $request = array();

    public $helpers = array();

    public $allow = true;


    /**
     * Inicia controller
     *
     * @param array $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        Sessions::start($this->app['route']['appName']);

        $this->args = $app['route']['arguments'];
        $this->request = array_merge($_GET, $_POST);

        $this->entity = str_replace('Controller', '', ucfirst(basename(str_replace('\\', '/', get_class($this)))));

        $this->setBaseVars();
        if ($app['FileSystem']->classExists($this->entity) === true) {
            $model = $this->entity;
            $this->$model = new $model();
        }

        $this->uses($this->entity);
        $DAO = $this->entity . 'DAO';

        $this->DAO = $this->$DAO;

        if (isset($_GET['hide']) === true) {
            $this->hideElements();
        }

    }

    public function detectAjax()
    {
    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    		$this->layout = false;
    	}
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
        $this->app['GPS']->route['layout'] = $layout;
    }

    public function welcomeToCupcake()
    {
        $this->hideElements();
        $this->layout = 'Flatlab';
        //
    }

    public function home()
    {
        if ($this->app['route']['entity'] !== '') {
            if (class_exists($this->app['route']['entity']) === false) {
    			$this->layout = 'Flatlab';
    			$this->setClassBody("body-404");
    			$this->hideElements();
    			return $this->renderView('Index/404.phtml');
    		}

    		$this->view = 'lista.phtml';
            $this->help('Lista');
            
            $class = $this->app['route']['entity'];
            $dados = new $class();
            $this->setDados($dados);
            
            $this->setResults($this->Lista->get());
            $this->setTitLista($this->app['route']['entity']);

            $class = $this->app['route']['entity'];
            $model = new $class();
            $this->setColunas($model->getColunasDaLista());
            
            $class = $this->app['route']['entity'];
            $dados = new $class();
            $this->setIcon($dados->getIcon());
        } else {
            $controllerPath = '\Apps\\' . $this->app['route']['appName'] . '\Controller\IndexController';
            $controller = new $controllerPath($this->app);
            $controller->home();
            $this->view = 'Landing' . DS . 'landingHome.phtml';
        }

    }


    public function novo()
    {
        $this->view = 'form.phtml';
        $this->help('Form');

        $class = $this->app['route']['entity'];
        $dados = new $class();
        $this->setDados($dados);

        $this->setIcon($dados->getIcon());
        $this->setSaida($dados->getSaida());
        $this->setCampos(array_keys(get_class_vars($this->app['route']['entity'])));
        $this->setTitulo($this->app['route']['entity']);
        $this->setMigalha('Cadastro');
        $this->setAcao('Cadastro');
        $this->setMsgFlash('Registro inserido com sucesso!');

    }


    public function editar()
    {
        $this->view = 'form.phtml';
        $this->help('Form');

        $dados = $this->DAO->find($this->args[0]);
        if ($dados === null) {
            $class = $this->app['route']['entity'];
            $dados = new $class();
        }

        $this->setDados($dados);
        $this->setSaida($dados->getSaida());
        
        $this->setIcon($dados->getIcon());
        $this->setCampos(array_keys(get_class_vars($this->app['route']['entity'])));
        $this->setTitulo($this->app['route']['entity']);
        $this->setMigalha('Edição');
        $this->setAcao('Edição');
        $this->setMsgFlash('Registro editado com sucesso!');

    }


    public function ver()
    {
    	$this->view = 'view.phtml';
        $this->help('Pages');

        $dados = $this->DAO->find($this->args[0]);
        $this->setDados($dados);

        $this->setCampos(array_keys(get_class_vars($this->app['route']['entity'])));
        $this->setTitulo($this->app['route']['entity']);
        $this->setMigalha('Registro nº ' . $this->args[0]);
        $this->setAcao('Detalhes');
        $this->setIcon($dados->getIcon());

    }


    /**
     * Prepara a tela de cadastro de cliente
     *
     * @return void
     */
    public function salvar()
    {
    	$this->layout = false;
        $dados = $this->DAO->listen($_POST);

        $this->DAO->salvar($dados);

        if (isset($_POST['flashMsg']) === true) {
        	Flash::alert($_POST['flashMsg'], 'information');
        }

        $saida = (isset($_POST['saida']) === true) ? $_POST['saida'] : '';
        if ($saida === 'view') {
            return '2;' . $this->getIndexController() . 'ver/' . $dados->getId();
        } elseif($saida === 'reload') {
        	return 'reload';
        } else {
            return '2;' . $this->getIndexController();
        }

    }


    public function uses($entity)
    {
        $entityDAO = $entity . 'DAO';
        $entityName = 'Apps\\' . $this->app['route']['appName'] . '\DAO\\' . $entityDAO;
        if ($this->app['FileSystem']->classExists($entityName) === true) {
            $this->$entityDAO = new $entityName($this->app);
        } else {
            $this->$entityDAO = new DAO($this->app, $entity);
        }

        return $this->$entityDAO;

    }


    public function help($helper, $entity=false)
    {
        $helperName = 'Apps\\' . $this->app['route']['appName'] . '\helpers\\' . ucfirst($helper);
        if ($this->app['FileSystem']->classExists($helperName) === false) {
            $helperName = 'Cupcake\helpers\\' . ucfirst($helper);
        }

        $this->helpers[] = $helper;
        $this->$helper = new $helperName($this->app, $entity);
        $this->$helper->request = $this->request;

        return $this->$helper;

    }


    /**
     * Seta as variaveis básicas
     *
     * @return void
     */
    public function setBaseVars()
    {
        $this->setLayoutAsset('//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/' . $this->app['GPS']->getLayoutAsset());
        $this->setCupcakeAsset('//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/' . $this->app['GPS']->route['cupcakeFolder'] . '/src/assets/');
        $this->setAppFolder($this->app['config']['folder']);
        $this->setAppAsset('//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/' . $this->app['GPS']->route['appsFolder'] . '/' . $this->app['GPS']->route['appName'] . '/' . $this->app['GPS']->route['assetFolder'] . '/');
        $this->setUploadsApp('//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/' . $this->app['GPS']->route['appsFolder'] . '/' . $this->app['GPS']->route['appName'] . '/' . $this->app['GPS']->route['uploadsFolder'] . '/');
        $this->app['GPS']->route['defaultApp'] = (isset($this->app['GPS']->route['defaultApp']) === false) ? $this->app['GPS']->route['appName'] : $this->app['GPS']->route['defaultApp'];
        $appName = ($this->app['GPS']->route['appName'] !== $this->app['GPS']->route['defaultApp']) ? $this->app['GPS']->route['appName'] . '/' : '';

        $url = '//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/';
        $this->setUrl($url);
        $this->setIndex($url . $appName);
        $this->setIndexPainel($url . $appName . 'painel/');
        $this->setIndexController($url . $appName . lcfirst($this->app['route']['entity']) . '/');
        $this->setHere($this->app['request']->getBasePath() . $this->app['request']->getPathInfo());

    }


    /**
     * Call dinamico para invocar metodos pelo fw
     *
     * @param string $name
     * @param array $arguments
     *
     * @return string
     */
    public function __call($name, $arguments)
    {
        $argument = $this->app['Vars']->validaArg($arguments);
        $var = $this->app['Vars']->$name($argument);

        if ($var === false) {
            $stacktrace = debug_backtrace();
            die('Método de Controller não encontrado: <b>' . $name . '</b> em ' . $stacktrace[0]['file'] . '#' . $stacktrace[0]['line']);
        }

        return $var;

    }


    /**
     * Renderiza o resultado do action no controller
     *
     * @param string $content
     *
     * @return string
     */
    public function render($content = null)
    {
        if ($this->layout === null) {
            $this->layout = $this->app['route']['layout'];
        } else {
            $this->setLayout($this->layout);
            $this->setBaseVars();
        }

        if ($this->view === null) {
            $this->view = $this->app['route']['view'];
        }

        $this->setModel();
        $this->setV($this->app['Vars']);
        $this->setArgs($this->args);

        if ($this->layout !== false) {
            if ($content === null) {
                $content = $this->view();
            }

            $this->setContent($content);
            return $this->getLayout();
        } else {
            if ($content !== null) {
                return $content;
            }

            return $this->view();
        }

    }


    public function setModel()
    {
        $setModel = 'set' . $this->entity;
        $model = $this->entity;
        if (isset($this->$model) === true) {
            $this->$setModel($this->$model);
        }

    }


    /**
     * Retorna uma view renderizada
     *
     * @return string
     */
    public function view()
    {
        return $this->app['Templating']->render($this->view, $this->app['Vars']->vars);

    }


    /**
     * Retorna uma view renderizada
     *
     * @return string
     */
    public function renderView($view)
    {
        return $this->app['Templating']->render($view, $this->app['Vars']->vars);

    }


    /**
     * Retorna um layout renderizado
     *
     * @return string
     */
    public function getLayout()
    {
        $layoutClassName = $this->app['GPS']->getLayoutClassName(ucfirst($this->layout));
        $layout = new $layoutClassName($this->app);
        $layout->index();

        return $this->app['Templating']->render($this->app['GPS']->getLayoutViewFile(), $this->app['Vars']->vars);
    }


    /**
     * Retorna um componente renderizado
     *
     * @param string $component
     *
     * @return void
     */
    public function useComponent($component)
    {
    	$this->setArgs($this->args);
        ob_start();
        $componentClassName = $this->app['GPS']->getComponentClassName($component, $this->layout);

        if ($componentClassName !== false) {
            $componentClass = new $componentClassName($this->app);

            if ($this->layout !== null) {
                $componentClass->setLayout($this->layout);
            }

            $vars = $this->app['Vars']->vars;
            $componentClass->index();
            extract($componentClass->app['Vars']->vars);
        }

        extract(isset($vars) === true ? $vars : $this->app['Vars']->vars);

        $componentViewFile = $this->app['GPS']->getComponentViewFile($component);
        require $componentViewFile;
        $content = ob_get_clean();

        $setVar = 'set' . $this->app['GPS']->urlToCamel($component);
        $this->$setVar($content);

        return true;

    }


    public function deletar()
    {
        $this->layout = false;
        $this->DAO->deletar($this->args[0]);

        if (isset($_GET['saida']) === true) {
            $saida = '4;' . urldecode($_GET['saida']);
        } else {
            $saida = '4;' . $this->getIndexController();
        }
        
        return $saida;

    }


    public function arg($index)
    {
        return $this->args($index);

    }


    public function args($index)
    {
        $realIndex = ($index - 1);
        if (isset($this->args[$realIndex]) === true) {
            if ($this->args[$realIndex] !== '') {
                return $this->args[$realIndex];
            }
        }

        return false;

    }


    public function request($index)
    {
        if (isset($_GET[$index]) === true) {
            return $_GET[$index];
        } else if (isset($_POST[$index]) === true) {
            return $_POST[$index];
        }

        return false;

    }


    public function hideElements()
    {
        $this->setNoVisibleElements(true);

    }


    public function showElements()
    {
        $this->setNoVisibleElements(false);

    }


    public function checkLogin()
    {
        if ($this->app['Auth']->restore() === null && $this->isAllowed() === false) {
            header('location: '.$this->getIndex() . 'login');
            die;
        }

    }


    public function allow()
    {
        $this->allow = true;

    }


    public function deny()
    {
        $this->allow = false;
        $this->checkLogin();

    }


    public function isAllowed()
    {
        return $this->allow;

    }

}

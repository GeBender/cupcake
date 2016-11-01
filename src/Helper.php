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

class Helper extends Controller
{

    public $classController;

    public $model;


    public function __construct($app, $routeEntity=false)
    {
        parent::__construct($app);

        if ($routeEntity === false) {
            $routeEntity = $app['route']['entity'];
        }

        $helperName = $this->entity;
        if ($app['route']['entity'] !== '') {
            $modelClass = $routeEntity;

            $this->entity = $routeEntity;

            ($this->app['GPS']->fs->classExists($modelClass) === true) ? $this->model = new $modelClass() : $this->model = false;
            $this->DAO = new DAO($app, $this->entity);
        }


        $setName = 'set'.$helperName;
        $this->$setName($this);

    }


}
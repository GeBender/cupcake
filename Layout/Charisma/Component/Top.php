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
namespace Layout\Charisma\Component;

class Top extends \Cupcake\Controller
{


    /**
     * Prepara e renderiza o topo
     *
     * @return void
     */
    public function index()
    {
        $this->setNomeSistema($this->app['config']['nomeSistema']);
        $this->setLogo($this->getLayoutAsset() . 'charisma/img/logo20.png');
        $this->setUserNome($this->app['Auth']->getInfo('userNome'));

    }


}
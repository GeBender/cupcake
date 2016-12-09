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
namespace Layout\Flatlab\Component;

class Sidebar extends \Cupcake\Controller
{

    public $layout = 'Flatlab';


    /**
     * Prepara e renderiza o sidebar
     *
     * @return void
     */
    public function index()
    {
        $this->setAssinanteNome($this->app['Auth']->getInfo('assinanteNome'));
        $this->setPerfil($this->app['Auth']->getInfo('perfil'));
    }
}

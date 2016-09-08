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

class Header extends \Cupcake\Controller
{

    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
        $this->setTitulo($this->app['config']['title']);
        // $this->setClassBody("body-404");
        if ($this->app['Vars']->getExtraHeaderA() === false) {

            $this->setExtraHeaderA(array());
        }

        if ($this->app['Vars']->getExtraHeaderB() === false) {
            $this->setExtraHeaderB(array());
        }

    }


}

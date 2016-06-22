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
namespace Layout\Charisma;

class Charisma extends \Cupcake\Controller
{

	public $layout = 'Charisma';

    /**
     * Carrega o componente do layout Charisma
     *
     * @return void
     */
    public function index()
    {
    	$this->setTitle($this->app['config']['title']);

        $this->useComponent('Header');
        $this->useComponent('Top');
        $this->useComponent('Sidebar');
        $this->useComponent('Footer');

    }


}
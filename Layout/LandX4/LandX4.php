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
namespace Layout\LandX4;

class LandX4 extends \Cupcake\Controller
{

    public $layout = 'LandX4';

	public function __construct($app)
	{
		parent::__construct($app);
	}
    
    /**
     * Carrega o componente do layout LandX4
     *
     * @return void
     */
    public function index()
    {
//         $this->setTitle($this->app['config']['title']);

//         $this->useComponent('Header');
//         $this->useComponent('Top');
//         $this->useComponent('Footer');

    }


}
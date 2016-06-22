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

use Cupcake\Controller;

class Header extends Controller
{

    public $layout = 'Charisma';

    public function __construct($app)
    {
        $this->allow();
    	parent::__construct($app);
        $this->hideElements();

    }
    
    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
        $this->setTitulo('Cupcake - The Rapid and Tasty Development Framework.');
        $this->setFavicon('favicon.ico');
        $this->setLogo('logo.png');
        if ($this->app['Vars']->getExtraHeaderA() === false) {
        	$this->setExtraHeaderA(array());
        }

        if ($this->app['Vars']->getExtraHeaderB() === false) {
            $this->setExtraHeaderB(array());
        }
    }


}

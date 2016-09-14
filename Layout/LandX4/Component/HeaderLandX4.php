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
namespace Layout\LandX4\Component;

use Cupcake\Controller;

class HeaderLandX4 extends Controller
{

    public $layout = 'LandX4';

    public function __construct($app)
    {
        $this->allow();
        parent::__construct($app);
    }

    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
        $this->setTitulo($this->getSistema()->getTitulo());
        $this->setDescription($this->getSistema()
            ->getDescricao());
        $this->setFavicon($this->getSistema()
            ->getFavicon());
        $this->setLogo($this->getSistema()
            ->getLogo());

        if ($this->app['Vars']->getExtraHeaderA() === false) {
            $this->setExtraHeaderA(array());
        }

        if ($this->app['Vars']->getExtraHeaderB() === false) {
            $this->setExtraHeaderB(array());
        }
    }
}

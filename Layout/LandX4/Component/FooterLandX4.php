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

use Cupcake\Flash;

class FooterLandX4 extends \Cupcake\Controller
{

    public $layout = 'LandX4';

    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
        $this->setAlert(Flash::getAlert());
        $this->setTopFull(Flash::getTopFull());
        $this->setModal(Flash::getModal());

        if ($this->app['Vars']->getExtraFooter() === false) {
            $this->setExtraFooter(array());
        }
    }
}

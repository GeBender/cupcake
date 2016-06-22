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

use Cupcake\Flash;

class Footer extends \Cupcake\Controller
{


    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
    	$this->addExtraFooter('<script type="text/javascript" src="' . $this->getLayoutAsset() . 'js/modernizr-custom.js"></script>');

        $this->setAlert(Flash::getAlert());
        $this->setTopFull(Flash::getTopFull());
        $this->setModal(Flash::getModal());

        if ($this->app['Vars']->getExtraFooter() === false) {
            $this->setExtraFooter(array());
        }

    }


}

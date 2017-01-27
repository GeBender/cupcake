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

use Cupcake\Flash;

class Footer extends \Cupcake\Controller
{

    public $layout = 'Flatlab';

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

        $this->setExtraFooter(['<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'jquery-ui/jquery-ui.min.js"></script>']);
    }
}

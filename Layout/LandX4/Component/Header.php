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

use Apps\CacambaNet\Controller\LogistickController;

class Header extends LogistickController
{

	public $layout = 'LandX4';

	public function __construct($app)
	{
		$this->allow();
		parent::__construct($app);
		$this->setLayoutAsset('//' . $this->app['request']->getHost() . $this->app['request']->getBasePath() . '/' . $this->app['GPS']->route['layoutFolder'] . '/' . $this->layout . '/'. $this->app['GPS']->route['assetFolder'] . '/');
	}

    /**
     * Prepara e renderiza o header
     *
     * @return void
     */
    public function index()
    {
        $this->setTitulo($this->getSistema()->getTitulo());
        $this->setDescription($this->getSistema()->getDescricao());
        $this->setKeywords($this->getSistema()->getKeywords());
        $this->setFavicon($this->getSistema()->getFavicon());

        if ($this->app['Vars']->getExtraHeaderA() === false) {
            $this->setExtraHeaderA(array());
        }

        if ($this->app['Vars']->getExtraHeaderB() === false) {
            $this->setExtraHeaderB(array());
        }

    }


}

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
namespace Cupcake\helpers;

use Cupcake\Helper;

class BootstrapTour extends \Cupcake\Helper
{

	public $name = 'tour';

	public $steps = [];

    public function __construct($app)
    {
    	parent::__construct($app);

    	$this->addExtraHeaderB('<link rel="stylesheet" type="text/css" href="' . $this->getCupcakeAsset() . 'css/bootstrap-tour.css">');
    	$this->addExtraFooter('<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'js/bootstrap-tour.js"></script>');
    }


    public function setName($name)
    {
    	$this->name = $name;
    }


    /**
	 * @param array $step
     */
    public function addStep($element, $title, $content, $placement=false)
    {
    	$step = [
    			'element' => $element,
    			'title' => $title,
    			'content' => $content
    	];
    	if ((bool) $placement === true) {
    		$step['placement'] = $placement;
    	}

    	$this->steps[] = $step;

    }



    public function load()
    {
    	$steps = '';
    	foreach ($this->steps as $step) {
    		$steps .= '
    				tour.addStep({
							element: "'.$step['element'].'",';
    		if (isset($step['placement']) === true) {
    			$steps .= '
    						placement: "'.$step['placement'].'",';
    		}
				$steps .= '
							title: "'.$step['title'].'",
							content: "'.$step['content'].'"
						});
					';
    	}

    	$this->addExtraFooter('<script type="text/javascript">
                function loadTour() {
                    	var tour = new Tour({
    						name: "'.$this->name.'",
    						backdrop: true,
			    			template: "<div class=\'popover tour\' style=\'background:#FFFFFF\'><div class=\'arrow\'></div><h3 class=\'popover-title\'></h3><div class=\'popover-content\'></div><div class=\'popover-navigation\'><button class=\'btn btn-warning btn-mini\' data-role=\'prev\'>« Voltar</button><span data-role=\'separator\'></span><button class=\'btn btn-warning btn-mini\' data-role=\'next\'>Próxima »</button><button class=\'right btn btn-warning btn-mini\' data-role=\'end\'>Ok, entendi!</button></div></nav></div>"
    					});
    					' . $steps .'
						return tour;
               };

        		$(document).ready(function() {
        			tour = loadTour();
        			tour.start();

        			$(\'.dark-screen\').click(function() {
        				tour.end();
    				});
    				$(\'.icon-help\').show();
        		});

               </script>');
    }

}
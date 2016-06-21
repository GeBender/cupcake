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

use \Cupcake\NoInjection as NoInjection;
use Doctrine\DBAL\Types\Type;

class FancyBox extends \Cupcake\Helper
{


    public function __construct($app)
    {
        parent::__construct($app);
        $this->addExtraHeaderA('<link rel="stylesheet" href="' . $this->getAppAsset() . 'fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />');

        $this->addExtraFooter('<script type="text/javascript" src="' . $this->getAppAsset() . 'fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>');
        $this->addExtraFooter('<script type="text/javascript">
                $(document).ready(function() {
                    $(".fancybox").fancybox();
                    $(".pop-forms").fancybox({
                		fitToView	: true,
                		width		: \'100%\',
                		height		: \'100%\',
                		autoSize	: false,
                		closeClick	: false,
                		openEffect	: \'none\',
                		closeEffect	: \'none\',
                		beforeClose : function() {
                			window.location.href = window.location.href;
                		}
                	});
                    $(".pop").fancybox({
                		fitToView	: true,
                		width		: \'100%\',
                		height		: \'100%\',
                		autoSize	: false,
                		closeClick	: false,
                		openEffect	: \'none\',
                		closeEffect	: \'none\'
                	});
                	$(".pop-peq").fancybox({
//                		fitToView	: true,
                		width		: \'75%\',
                    	height		: \'75%\',
                		autoSize	: false,
                		closeClick	: false,
                		openEffect	: \'none\',
                		closeEffect	: \'none\',
                		beforeClose : function() {
                			window.location.href = window.location.href;
                		}
                	});
               });
               </script>');

    }


}
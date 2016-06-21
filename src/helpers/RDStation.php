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
use PHPMailer;

class RDStation extends \Cupcake\Helper
{

    public $url;
    public $token;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->url = 'https://www.rdstation.com.br/api/1.3/conversions';
        $this->token = $this->app['config']['RDStation']['token'];

    }


    public function sendLead($data)
    {
    	$data['token_rdstation'] = $this->token;
    	
    	$options = array(
    			'http' => array(
    					'header'  => "Content-type: application/json\r\n",
    					'method'  => 'POST',
    					'content' => json_encode($data),
    			),
    	);
    	$context  = stream_context_create($options);
    	
    	return file_get_contents($this->url, false, $context);
    	
    }


}
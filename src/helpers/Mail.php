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

class Mail extends \Cupcake\Helper
{

    public $mailer;

    public function __construct($app)
    {
        parent::__construct($app);

        $mailConf = $this->app['config']['mail'];

        $this->mailer = new \PHPMailer();

        $this->mailer->isSMTP();
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->Host = $mailConf['host'];
        $this->mailer->Port = $mailConf['port'];
        $this->mailer->Username = $mailConf['username'];
        $this->mailer->Password = $mailConf['password'];

        $this->mailer->setFrom($mailConf['from'], $mailConf['fromName']);
        $this->mailer->addReplyTo($mailConf['from'], $mailConf['fromName']);

    }

    public function debug()
    {
    	$this->mailer->SMTPDebug = 4;
    }

    public function send($subject, $view)
    {
        $this->layout = 'emails';
        $this->mailer->Subject = $subject;
        $body = $this->renderView('Emails'.DS.$view.'.phtml');

        $this->mailer->msgHTML($body);
        $result = $this->mailer->send();

        return $result;

    }
}
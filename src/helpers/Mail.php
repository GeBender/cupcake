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

use SendGrid;

class Mail extends \Cupcake\Helper
{

    public $mailer;

    public $from;

    public $to;

    public $debug;

    public $layout = 'FlatlabEmails';

    public function __construct($app)
    {
        parent::__construct($app);

        $mailConf = $this->app['config']['mail'];

        $this->mailer = new \SendGrid($this->app['config']['mail']['sendGridKey']);
        //dbg($this->mailer, true);
        //$this->mailer = new \PHPMailer();

        //$this->mailer->isSMTP();
        //$this->mailer->CharSet = 'UTF-8';
        //$this->mailer->SMTPAuth = true;
        ///$this->mailer->SMTPSecure = 'ssl';
        //$this->mailer->Host = $mailConf['host'];
        //$this->mailer->Port = $mailConf['port'];
        //$this->mailer->Username = $mailConf['username'];
        //$this->mailer->Password = $mailConf['password'];

        $this->from = new SendGrid\Email($mailConf['fromName'], $mailConf['from']);
    }

    public function addFrom($email, $nome = null)
    {
        $this->from = new SendGrid\Email($nome, $email);
    }

    public function addAddress($email, $nome = null)
    {
        $this->to = new SendGrid\Email($nome, $email);
    }

    public function debug()
    {
        $this->debug = true;
    }

    public function send($subject, $view)
    {
        $this->layout = 'FlatlabEmails';

        $body = $this->renderView('Emails'.DS.$view.'.phtml');
        $body = $this->render($body);

        echo $body;
        die(true);

        $content = new SendGrid\Content("text/html", $body);

        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);

        $response = $this->mailer->client->mail()->send()->post($mail);

        if ($this->debug) {
            dbg($response->statusCode());
            dbg($response->headers());
            dbg($response->body());
        }

        return ($response->statusCode() == 202) ? true : false;
    }
}

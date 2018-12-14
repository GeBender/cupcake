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

use Google_Client;
use Google_Service_Oauth2;

use Apps\Logistick\Controller\IndexController;
use Apps\Logistick\Controller\LogistickController;

class GoogleOAuth extends \Cupcake\Helper
{

    public $client_id;
    public $client_secret;
    public $redirect_uri;
    public $simple_api_key;

    public $client;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->client = new Google_Client();
        $this->client->setApplicationName($app['config']['nomeSistema']);
        $this->client->setClientId($app['config']['oauth-google-api-client-id']);
        $this->client->setClientSecret($app['config']['oauth-google-api-secret-key']);
        $this->client->setRedirectUri('http://lvh.me');
        $this->client->setDeveloperKey($app['config']['oauth-google-api-key']);
        $this->client->addScope("https://www.googleapis.com/auth/userinfo.email");

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
          $this->client->setAccessToken($_SESSION['access_token']);
        }

        if (isset($_GET['code'])) {
            $IndexController = new IndexController($this->app);
            $IndexController->entrarComGmail();
            // $this->proccessCode($_GET['code']);
        }

    }

    public function proccessCode($code) {

        $this->auth($code);
        $userData = $this->getUserData();

        if(!empty($userData)) {
            $this->uses('Usuarios');
            if(!$this->UsuariosDAO->recuperarLogin($userData->email)) {
                $this->newAccess($userData);
            }
        }

        $IndexController = new IndexController($this->app);
        $saida = $IndexController->entrar($this->UsuariosDAO->loginByGoogleOAuthId($userData->id));
        $saida = explode(";", $saida);
        
        header('location:' . $saida[1]);
        die;
    }

    public function newAccess($userData) {
        $IndexController = new IndexController($this->app);

        $_POST['Leads']['nome'] = $userData->name;
        $_POST['Leads']['email'] = $userData->email;
        $_POST['Leads']['callToAction'] = "Entrar com Google";
        $_POST['Leads']['google_id'] = $userData->id;
        $_POST['landing'] = "";

        return $IndexController->testeGratis(false, false);
    }
    
    public function logout() {
      unset($_SESSION['access_token']);
      $this->client->revokeToken();
      header('Location: ' . filter_var('/', FILTER_SANITIZE_URL));
    }

    public function auth($code) {
        $this->client->authenticate($code);
        $this->client->setApprovalPrompt('consent');

        $_SESSION['access_token'] = $this->client->getAccessToken();
    }

    public function getUserData() {
        $service = new Google_Service_Oauth2($this->client);
        return $service->userinfo->get();
    }

}
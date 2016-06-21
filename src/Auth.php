<?php
namespace Cupcake;

use Cupcake\Cookies;

class Auth
{

    public $authName = 'authCupcake';


    public function register($sistema, $assinanteId, $userId, $extra=array())
    {
    	$cupcakeAuth = array(
                'sistema' => $sistema,
                'assinanteId' => $assinanteId,
                'userId' => $userId
        );
        $cupcakeAuth = array_merge($cupcakeAuth, $extra);
        Cookies::save($this->authName, $cupcakeAuth);

    }


    public function restore()
    {
        return Cookies::restore($this->authName);

    }


    public function unAuth()
    {
        Cookies::delete($this->authName);

    }


    public function assinanteId()
    {
        $auth = Cookies::restore($this->authName);
        return $auth['assinanteId'];

    }


    public function sistema()
    {
        $auth = Cookies::restore($this->authName);
        return $auth['sistema'];

    }


    public function userId()
    {
        $auth = Cookies::restore($this->authName);
        return $auth['userId'];

    }


    public function getInfo($var)
    {
        $auth = Cookies::restore($this->authName);
        return $auth[$var];

    }


    public function getAssinante($DAO)
    {
        return $DAO->find($this->assinanteId());

    }

    public function getUsuario($DAO)
    {
    	return $DAO->find($this->userId());

    }

}
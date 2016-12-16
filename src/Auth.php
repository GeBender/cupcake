<?php

namespace Cupcake;

use Cupcake\Cookies;

class Auth
{

    const AUTH_NAME = 'authCupcake2';


    public function register($sistema, $assinanteId, $userId, $extra = array())
    {
        $cupcakeAuth = array(
                'sistema' => $sistema,
                'assinanteId' => $assinanteId,
                'userId' => $userId
        );
        $cupcakeAuth = array_merge($cupcakeAuth, $extra);
        Cookies::save(self::AUTH_NAME, $cupcakeAuth);
    }


    public function restore()
    {
        return Cookies::restore(self::AUTH_NAME);
    }


    public function unAuth()
    {
        Cookies::delete(self::AUTH_NAME);
    }


    public function assinanteId()
    {
        $auth = Cookies::restore(self::AUTH_NAME);
        return $auth['assinanteId'];
    }


    public function sistema()
    {
        $auth = Cookies::restore(self::AUTH_NAME);
        return $auth['sistema'];
    }


    public function userId()
    {
        $auth = Cookies::restore(self::AUTH_NAME);
        return $auth['userId'];
    }


    public function getInfo($var)
    {
        $auth = Cookies::restore(self::AUTH_NAME);
        return @$auth[$var];
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

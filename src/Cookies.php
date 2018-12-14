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
namespace Cupcake;

class Cookies
{


    public static function save($var, $value, $expire = 0)
    {
        var_dump($var, $value);
        $result = setcookie($var, Crypt::encode($value), $expire);
        var_dump($result, $_COOKIE); die;
    }


    public static function restore($v)
    {
        // var_dump($_COOKIE[$v]);
        // var_dump($_COOKIE); die;
        $data = null;
        (empty($_COOKIE[$v]) === false) ? $data = Crypt::decode($_COOKIE[$v]) : $data = null;
        return $data;
    }


    public static function delete($var)
    {
        setcookie($var, '');
    }
}

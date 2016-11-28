<?php
/**
 * CupcakePHP : Rapid and Tast Development Framework
 *
 * PHP version 5.4.12
 *
 * @category  Cupcake
 * @package   Core
 * @author    Ge Bender <gesianbender@gmail.com>
 * @copyright 2014 - Cupcake Project
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: <git_id>
 * @link      http://cupcakephp.com.br
 */

/**
 * Classe com metodos globais para Debug
 *
 * @category Cupcake
 * @package  Core
 * @author   Ge Bender <gesianbender@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://cupcakephp.com.br
 */

class Debug
{

    static $isCupcaker = false;

    public static function setCupcaker($is)
    {
        self::$isCupcaker = $is;
    }

    /**
     * Debuga uma baviavel qualquer
     *
     * @param mixed $var > A variavel
     * @param bool $die > Se vai parar o script quando debugar
     * @param number $backtrace > indice para linha e aqruivo do backTrace
     *
     * @return string
     */
    public static function dbg($var, $die = false, $backtrace = 1)
    {
        if (self::$isCupcaker === true) {
            $bt = debug_backtrace();
            echo '<div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>' . $bt[$backtrace]['file'] . ' # ' . $bt[$backtrace]['line'] . '</strong><br>';
            var_dump($var);
            echo '</div>';
            if ($die === true) {
                die();
            } else {
                echo '<BR/>';
            }
        }
    }

    public static function isCupcaker()
    {
        return self::$isCupcaker;
    }
}


/**
 * Funcao Global para facilitar ainda mais o acesso ao debug
 *
 * @param mixed $var > O que precisa ser debugado
 * @param bool $die > Se vai parar o script apos o debug
 * @param number $backtrace > Linha do backtrace
 *
 * @return string
 */
function dbg($var, $die = false, $backtrace = 1)
{
    Debug::dbg($var, $die, $backtrace);
}


/**
 * Funcao Global para facilitar ainda mais o acesso ao debug
 *
 * @param mixed $var > O que precisa ser debugado
 * @param bool $die > Se vai parar o script apos o debug
 * @param number $backtrace > Linha do backtrace
 *
 * @return string
 */
function debug($var, $die = false, $backtrace = 1)
{
    Debug::dbg($var, $die, $backtrace);
}

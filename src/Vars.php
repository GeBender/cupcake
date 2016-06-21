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

class Vars
{

    public $vars = array();


    /**
     * Call dinamico para registar e ler variaveis
     *
     * @param string $name
     * @param array $arguments
     *
     * @return string|bool
     */
    public function __call($name, $arguments)
    {
        $var = lcfirst(substr($name, 3));
        $prefix = substr($name, 0, 3);

        if ($prefix === 'has') {
            return isset($this->vars[$var]);
        } else if ($prefix === 'set') {
            $this->vars[$var] = $arguments[0];
            return true;
        } else if ($prefix === 'add') {
            $paramArray = @$this->vars[$var];
            $paramArray[] = $arguments[0];
            $this->vars[$var] = $paramArray;
            return true;
        } else if ($prefix === 'sum') {
            $this->vars[$var] += $arguments[0];
            return true;
        } else if ($prefix === 'cct') {
            $this->vars[$var] .= $arguments[0];
            return true;
        } else if (isset($this->vars[$var]) === true) {
            return $this->vars[$var];
        } else if (isset($this->vars[$name]) === true) {
            return $this->vars[$name];
        }//end if

        return false;

    }


    /**
     * Valida se o argumento é válido
     *
     * @param array $arg
     *
     * @return mixed
     */
    public function validaArg($arg)
    {
        if (isset($arg[0]) === false) {
            return false;
        }

        return $arg[0];

    }


    /**
     * Retorna todas as variáveis
     *
     * @return array
     */
    public function get()
    {
        return $this->vars;

    }


}

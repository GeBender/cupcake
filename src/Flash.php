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

use Cupcake\Sessions;

class Flash
{


    public static function alert($msg)
    {
        Sessions::save('flashAlert', $msg);

    }


    public static function getAlert()
    {
        $alert = Sessions::restore('flashAlert');
        Sessions::delete('flashAlert');

        return $alert;

    }


    public static function topFull($msg, $type='information')
    {
        Sessions::save('flashTopFull', $msg);
        Sessions::save('flashTopFullType', $type);

    }


    public static function getTopFull()
    {
        $msg = Sessions::restore('flashTopFull');
        $type = Sessions::restore('flashTopFullType');

        Sessions::delete('flashTopFull');
        Sessions::delete('flashTopFullType');
        
        if ($msg !== null) {
            return '"text":"'.$msg.'","layout":"top","type":"'.$type.'"';
        }

        return false;

    }


    public static function modal($layout)
    {
        Sessions::save('flashModal', $layout);

    }


    public static function getModal()
    {
        $modal = Sessions::restore('flashModal');
        Sessions::delete('flashModal');

        return $modal;

    }


}
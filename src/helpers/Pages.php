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

use Doctrine\ORM\PersistentCollection;

class Pages extends \Cupcake\Helper
{


	public function viewLista($result, $field) {
		$viewer = 'viewLista'.ucfirst($field);
		if(method_exists($result, $viewer) === true) {
			return $result->$viewer();
		}
		return $this->showData($result, $field);
	}
	
    public function showData($result, $field)
    {	$viewer = 'showData'.ucfirst($field);
    	if(method_exists($result, $viewer) === true) {
    		return $result->$viewer();
    	}
    	
    	$getter = 'get'.ucfirst($field);
    	
    	if ($result->$getter() instanceof \DateTime) {
    	    return date('d-m-Y - H:i \h.', $result->$getter()->getTimestamp());
        } else if($result->$getter() instanceof PersistentCollection) {
        	$mapping = $result->$getter()->getMapping();
        	$mappedClass = $mapping['targetEntity'];
        	$mapped = new $mappedClass();
        	
        	$compl = ($result->$getter()->count() > 0 ) ? $mapped->getPlural() : $mapped->getSingular();
        	return $result->$getter()->count() . ' ' . $compl;
        } else if (is_object($result->$getter()) === true) {
        	(property_exists($result->$getter(), 'identifier') === true) ? $getter2 = 'get' . ucfirst($result->$getter()->getIdentifier()) : $getter2 = 'getId';
            return $result->$getter()->$getter2();
        }
        return $result->$getter();

    }


    public function ptbrToFloat($valor)
    {
        return (float) str_replace(',', '.', str_replace('.', '', $valor));

    }


    public function sqlOrdenableDateToPtBr($sqlOrderableDate, $padrao)
    {
        ((bool) $sqlOrderableDate === false) ? $ptBr = false : $ptBr = substr($sqlOrderableDate, 6).'/'.substr($sqlOrderableDate, 4, 2).'/'.substr($sqlOrderableDate, 0, 4);
        return $ptBr;

    }


}

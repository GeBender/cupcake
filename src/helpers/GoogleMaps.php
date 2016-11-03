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

class GoogleMaps extends \Cupcake\Helper
{


    public function geocode($stringAddress)
    {
        $buscaUrl = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($stringAddress);
        $json = file_get_contents($buscaUrl);

        return json_decode($json, true);

    }


    public function isGeocodeReachable($geocode)
    {
    	if(is_array($this->geoTypes($geocode)) === false) {
    		return false;
    	}

        if (in_array('street_address', $this->geoTypes($geocode)) === true || in_array('route', $this->geoTypes($geocode)) === true) {
            return true;
        }

        return false;

    }


    public function geoTypes($geocode)
    {
        if (isset($geocode['results'][0]['types']) === true) {
            return $geocode['results'][0]['types'];
        }

        return false;

    }


    public function getGeocodeUrlAddress($geocode)
    {
        return urlencode(@$geocode['results'][0]['formatted_address']);

    }

    public function getLatitude($geocode)
    {
    	return (isset($geocode['results'][0])) ? $geocode['results'][0]['geometry']['location']['lat'] : false;
    }

    public function getLongitude($geocode)
    {
    	return isset($geocode['results'][0]) ? $geocode['results'][0]['geometry']['location']['lng'] : false;
    }


}
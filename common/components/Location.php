<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.08.2016
 * Time: 11:11
 */

namespace common\components;

use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\overlays\Animation;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\InfoWindowOptions;
use dosamigos\google\maps\overlays\MarkerOptions;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\overlays\PolylineOptions;
use dosamigos\google\maps\services\DirectionsRenderer;
use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\services\DirectionsWayPoint;
use dosamigos\google\maps\services\GeocodingClient;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\services\TravelMode;
use dosamigos\google\maps\UnitsType;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;


class Location
{

    /**
     * @param $address
     * $address must be like 'Stationsplein, 1012 AB Amsterdam',
     * @param $region
     * $region must be like 'Netherlands',
     * @return array
     */
    public static function getLatLngByAddress($address, $region)
    {
        $data_arr = array(
            'lat' => null,
            'lng' => null,
        );
		//$address = utf8_encode($address);
        // google map geocode api url
        $address = urlencode(trim($address).'+'.trim($region));
        //$url = "https://maps.google.com/maps/api/geocode/json?address={$address}";
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyBppM_E6UzDSxTcB-SUjtqy5KnfKs9W4MY";
        // get the json response
        $resp_json = file_get_contents($url);
        // decode the json
        $resp = json_decode($resp_json, true);
        if ($resp['status'] == 'OK') {
            // get the important data
            $lat = $resp['results'][0]['geometry']['location']['lat'];
            $lng = $resp['results'][0]['geometry']['location']['lng'];
            // verify if data is complete
            if ($lat && $lng) {
                // put the data in the array
                $data_arr['lat'] = $lat;
                $data_arr['lng'] = $lng;
            }
        }
        return $data_arr;
    }

    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
    public static function getAddressByLatLng($lat, $lng)
    {
        $latlng = urlencode($lat.','.$lng);
        $url = $url = "https://maps.google.com/maps/api/geocode/json?latlng={$latlng}";
        $resp_json = file_get_contents($url);
        $resp = json_decode($resp_json, true);
        $address['address'] = $resp["results"][0]['address_components'][0]['long_name'];
        $address['city'] = $resp["results"][0]['address_components'][1]['long_name'];
        $address['state'] = $resp["results"][0]['address_components'][2]['long_name'];
        $address['country'] = $resp["results"][0]['address_components'][3]['long_name'];
        return $address;
    }

    /**
     * @param $coordinats
     * $coordinats = ['lat' => $latitude, 'lng' => $longitude]
     * @return string
     */
    public static function ShowLocation($coordinates, $zoom = null)
    {
        $center = new LatLng($coordinates);
        $map = new Map([
            'center' => $center,
            'zoom' => 12,
            'scrollwheel'=> false,
        ]);
        $marker = new Marker([
            'position' => $center
        ]);
        $marker->attachInfoWindow(
            new InfoWindow([
                'content' => '<p>Yuor position by choosen address!</p>'
            ])
        );
        $map->addOverlay($marker);

        return $map->display();
    }

    /**
     * @param $RepairerCoord
     * @param $CustomerCoord
     * @param null $content
     * @return string
     */
    public static function ShowWayToCustomer($RepairerCoord, $CustomerCoord, $content=null)
    {
        $isValid = isset($CustomerCoord['lat']) && ($CustomerCoord['lng'] != '');
        $isValid = isset($CustomerCoord['lng']) && ($CustomerCoord['lng'] != '') && $isValid;
        $isValid = isset($RepairerCoord['lat']) && ($RepairerCoord['lat'] != '') && $isValid;
        $isValid = isset($RepairerCoord['lng']) && ($RepairerCoord['lng'] != '') && $isValid;
        if($isValid){
            $coord = new LatLng($CustomerCoord);
            $map = new Map([
                'center' => $coord,
                'zoom' => 12,
                'scrollwheel'=> false,
            ]);

// lets use the directions renderer
            $customerCoord = new LatLng($CustomerCoord);
            $repairerCoord = new LatLng($RepairerCoord);

            $directionsRequest = new DirectionsRequest([
                'origin' => $repairerCoord,
                'destination' => $customerCoord,
                'travelMode' => TravelMode::DRIVING
            ]);
// Lets configure the polyline that renders the direction
            $polylineOptions = new PolylineOptions([
                'strokeColor' => '#FFAA00',
                'draggable' => false
            ]);
            $markerOptions = new MarkerOptions([
                'animation' => Animation::BOUNCE,
            ]);
// Now the renderer
            $directionsRenderer = new DirectionsRenderer([
                'map' => $map->getName(),
                'markerOptions' => $markerOptions,
                'polylineOptions' => $polylineOptions
            ]);
// Finally the directions service
            $directionsService = new DirectionsService([
                'directionsRenderer' => $directionsRenderer,
                'directionsRequest' => $directionsRequest
            ]);
// Thats it, append the resulting script to the map
            $map->appendScript($directionsService->getJs());
            return $map->display();
        }else{
            false;
        }

    }

    /**
     * @param $coord1
     * @param $coord2
     * @param string $unit
     * @return float
     */
    public static function getDistance($coord1, $coord2, $unit = UnitsType::KILOMETERS)
    {
        $coord = new LatLng($coord1);
        $coor = new LatLng($coord2);
        $distance = $coord->exactDistanceSLCFrom($coor, $unit);
        return $distance;
    }

    /**
     * @param $Coordinates
     * @param $coord
     * @param int $less
     * @param string $unit
     * @return array
     */
    public static function getNearCoordinatesFrom($Coordinates, $coord, $less = 5, $unit = UnitsType::KILOMETERS)
    {
        $NearestCoord =[];
        $arrayDiest = [];
        foreach ($Coordinates as $key=> $coordinate){
            $dist = self::getDistance($coordinate,$coord,$unit);
            if( $dist < $less){

                $arrayDiest[$key]=$dist;
                $NearestCoord[$key]=$coordinate;
            }
        }
//        $minDist = min(array_values($arrayDiest));
//        $minDistKey = array_search($minDist, $arrayDiest);
//        $NearestCoord = ArrayHelper::filter($NearestCoord,[$minDistKey]);
        return $NearestCoord;
    }
}
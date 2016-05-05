<?php


class Distance{
    
    private $default_lat;
    private $default_lon;
    private $default_api;
    private $earth_radius = 6371;
    private $default_radius = 500;
    private $debug;
    private $output;
    
    public function __construct($lat, $lon){
        $this->default_lat = floatval($lat);
        $this->default_lon = floatval($lon);
    }
    
    
    public function proceedData($apiUrl, $debug = false){
        $this->debug = $debug;
        
        $response = file_get_contents($apiUrl);
        $src = json_decode($response,true);
        ksort($src);

            $result = array_map(array($this, 'getResultByVincentyFormula'), $src);

        return array_unique($result);
    }
    
    protected function getResultByVincentyFormula($data){
        
        $latitude1  = deg2rad($this->default_lat);
        $longitude1 = deg2rad($this->default_lon);
        $latitude2  = deg2rad($data['lat']);
        $longitude2 = deg2rad($data['lon']);
        
        $dLat = $latitude2 - $latitude1;  
        $dLon = $longitude2 - $longitude1; 
        
        //Vincenty Formula
          $numerator = pow(cos($latitude2) * sin($dLon) , 2) + pow( cos($latitude1) * sin($latitude2) - sin($latitude1) * cos($latitude2) * cos($dLon), 2 );
          $denominator = sin($latitude1)*sin($latitude2)+cos($latitude1)*cos($latitude2)*cos($dLon);
          $res = atan2(sqrt($numerator),$denominator) * $this->earth_radius;
        
        //Haversin Formula  
        //$a = sin($dLat/2) * sin($dLat/2) + cos($latitude1) * cos($latitude2) * sin($dLon/2) * sin($dLon/2);  
        //$c = 2 * asin(sqrt($a));  
        //$d = $this->earth_radius * $c;  
        
        if($this->debug === false){
            if($res <= $default_radius){
                $this->output = urldecode($data['city']);
            }else{
                $this->output = '';    
            }
        }else{
            if($res <= $default_radius){
                $this->output = $res;
            }
        }
        return $this->output;
    }
    
    
    
}
//TODO:: In test running mode it is needed to comment it out
$bublin_lat = "53.333";
$dublin_lon = "-6.267";
$api = 'https://gist.githubusercontent.com/mwtorkowski/16ca26a0c072ef743734/raw/2aa20e8de9f2292d58a4856602c1f0634d8611a7/cities.json';
$output = new Distance($bublin_lat, $dublin_lon);
$t = $output->proceedData($api);
foreach ($t as $value) {
    echo $value . '<br />';
}
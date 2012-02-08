<?php
include (DIR_WS_CLASSES.'geoip.inc');
class geo_locator
{
    var $lastCheckedIp;
    
    function geo_locator()
    {
    }
    
    function locate($ip)
    {
        $gi = geoip_open(DIR_WS_CLASSES.'GeoIP.dat', GEOIP_STANDARD);
        
        $this->lastCheckedIp = $ip;
        $cCode = geoip_country_code_by_addr($gi, $ip);
        
        return $cCode;
    }
    
    function getLastCheckedIp() { return $this->lastCheckedIp; }
    
    function isValid($c) { return isset($c); }
    
    function getCountryIdFromDb($code)
    {
        $query = "select countries_id from " . TABLE_COUNTRIES . " where countries_iso_code_2 = '" . $code . "'";
        $check_country_id = tep_db_query($query);
        $country_id = tep_db_fetch_array($check_country_id);
        
        return $country_id[countries_id];
    }
}
/*
class geo_locator
{
    private $url = 'http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=';
    var $lastCheckedIp;
    
    function geo_locator()
    {
        //117.20.16.220
    }
    
    function locate($ip)
    {
        $this->lastCheckedIp = $ip;
        $tags = get_meta_tags($this->url.$ip);
        
        if ($this->isValidIp($tags))
        {
            return $this->constructLocationDetails($tags);
        }
    }
    
    function isValidIp($t)
    {
        return $t['known']==TRUE ? TRUE : FALSE;
    }
    
    function getLastCheckedIp() { return $this->lastCheckedIp; }
    
    private function constructLocationDetails($t)
    {
        $locationDetails = 
            array(
                'iso_code_2' => $t[iso2],
                'iso_code_3' => $t[iso2],
                'country' => $t[country],
                'state' => $t[region],
                'statecode' => $t[regioncode],
                'city' => $t[city]
            );
        return $locationDetails;
    }
}
*/
?>
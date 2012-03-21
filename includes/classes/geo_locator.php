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
    
    function getCountryInfoFromDb($code)
    {
        $query = "select countries_id,countries_name,countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_iso_code_2 = '" . $code . "'";
        $check_country_id = tep_db_query($query);
        $country_info = tep_db_fetch_array($check_country_id);
        
        return $country_info;
    }
}
?>
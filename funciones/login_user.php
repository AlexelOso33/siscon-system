<?php
    
function llevarUserAgent(){
    if ( isset($_SERVER) ) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    } else {
        global $HTTP_SERVER_VARS;
        if (isset( $HTTP_SERVER_VARS)){
            $user_agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
        } else {
            global $HTTP_USER_AGENT;
            $user_agent = $HTTP_USER_AGENT;
        }
    }
    return $user_agent;
}

function tomarIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}

function getOS() { 
    $user_agent = llevarUserAgent();
    $os_array =  array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/Windows Phone 10.0/i' =>  'Windows Phone',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
      );
    //
    $os_platform = "Unknown OS Platform";
    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    return $os_platform;
}

function getBrowser() {
    $user_agent = llevarUserAgent();
    $browser_array = array(
        '/msie/i'       =>  'Internet Explorer',
        '/firefox/i'    =>  'Firefox',
        '/safari/i'     =>  'Safari',
        '/chrome/i'     =>  'Chrome',
        '/edge/i'       =>  'Edge',
        '/opera/i'      =>  'Opera',
        '/netscape/i'   =>  'Netscape',
        '/maxthon/i'    =>  'Maxthon',
        '/konqueror/i'  =>  'Konqueror',
        '/mobile/i'     =>  'Handheld Browser'
      );
    $browser = "Unknown Browser";
    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }
    return $browser;
}

?>
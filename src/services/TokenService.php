<?php
namespace Tecnolaw\Authorization\Services;
use  Tecnolaw\Authorization\Models\Token;
use  Tecnolaw\Authorization\Models\RecoveryPassword;

class TokenService
{
    protected $user = null;
	protected $back_office = false;
	function __construct($user,$back_office=false) {
        $this->user = $user;
        $this->back_office = $back_office;
    }
    /**
     * Funcion para generar y guardar un token, en el guardado
     * tambien se incluye la ip mediante $_SERVER
     *
     * @param  App\User $user
     * @return stirng token
     */
    public function generate($reset_pass=false)
    {
    	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $f=env('FORMAT_DATETIME_DB');
        $systemInfo = $this->systemInfo();
        $browserInfo = $this->browser();
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        if ($reset_pass==true) {
            $dataToken = RecoveryPassword::create([
                'user_id'           => $this->user->getKey(),
                'token'             => $token,
                'user_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'expiration_date'   => null, //date($f, strtotime(date($f) . ' + 1 day')),
                'email_send'        => $this->user->email,
            ]);
        } else {
            $dataToken = Token::create([
                'user_id'           => $this->user->getKey(),
                'token'             => $token,
                'user_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'browser'           => $browserInfo,
                'ip'                => $ip,
                'device'            => $systemInfo['device'],
                'os'                => $systemInfo['os'],
                'expiration_date'   => null, //date($f, strtotime(date($f) . ' + 1 day'))
                'back_office'       => $this->back_office, //es un token para acceder al back_office
            ]);
        }
        return $dataToken->token;
    }
    /**
     * Detecta OS y divice en $_SERVER['HTTP_USER_AGENT']
     *
     * @return array systemInfo
     */
    public static function systemInfo()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform    = "Unknown OS Platform";
        $os_array       = array(
            '/windows phone 8/i'    => 'Windows Phone 8',
            '/windows phone os 7/i' => 'Windows Phone 7',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP 5.1',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'
        );
        $found = false;
        $device = '';
        foreach ($os_array as $regex => $value)
        {
            if($found)
            {
                break;
            }
            else if (preg_match($regex, $user_agent))
            {
                $os_platform = $value;
                $device = !preg_match(
                    '/(windows|mac|linux|ubuntu)/i',
                    $os_platform
                ) ? 'MOBILE':( preg_match('/phone/i', $os_platform) ? 'MOBILE': 'SYSTEM' );
                $found = true;
            }
        }
        $device = !$device ? 'SYSTEM': $device;
        $systemInfo = array('os' => $os_platform,'device' => $device);
        return $systemInfo;
    }
    /**
     * Detecta browser en $_SERVER['HTTP_USER_AGENT']
     *
     * @return string browser
     */
    public static function browser()
    {
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $browser        = "Unknown Browser";
        $browser_array  = array(
            '/msie/i'       => 'Internet Explorer',
            '/firefox/i'    => 'Firefox',
            '/safari/i'     => 'Safari',
            '/chrome/i'     => 'Chrome',
            '/opera/i'      => 'Opera',
            '/netscape/i'   => 'Netscape',
            '/maxthon/i'    => 'Maxthon',
            '/konqueror/i'  => 'Konqueror',
            '/mobile/i'     => 'Handheld Browser'
        );
        $found = false;
        $device = '';
        foreach ($browser_array as $regex => $value)
        {
            if($found)
                break;
            else if (preg_match($regex, $user_agent,$result))
            {
                $browser = $value;
                $found = true;
            }
        }
        return $browser;
    }
}

<?php
namespace IP2ProxyCakePHP\Controller;

// Web Service Settings
if((defined('IP2LOCATION_IO_API_KEY')) && (!defined('USE_IO'))) {
    define('USE_IO', true);
} else  {
    if (!defined('USE_IO')) {
        define('USE_IO', false);
    }
    if(!defined('IP2PROXY_API_KEY')) {
        define('IP2PROXY_API_KEY', 'demo');
    }

    if(!defined('IP2PROXY_PACKAGE')) {
        define('IP2PROXY_PACKAGE', 'PX1');
    }

    if(!defined('IP2PROXY_USESSL')) {
        define('IP2PROXY_USESSL', false);
    }
}

/**
 * IP2ProxyCores Controller
 */
class IP2ProxyCoresController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        //
    }

    public function get($ip, $db = '')
    {
        if($db == '') {
            $obj = new \IP2Proxy\Database(ROOT . DS . 'vendor' . DS . 'ip2location' . DS . 'ip2proxy-cakephp' . DS . 'src' . DS . 'Data' . DS . 'IP2PROXY.BIN', \IP2Proxy\Database::FILE_IO);
        } else {
            $obj= new \IP2Proxy\Database($db, \IP2Proxy\Database::FILE_IO);
        }

        try {
            $records = $obj->lookup($ip, \IP2PROXY\Database::ALL);
        } catch (Exception $e) {
            return null;
        }

        $obj->close();
        return $records;
    }

    public function getWebService($ip)
    {
        if (USE_IO) {
            // Using IP2Location.io API
            $ioapi_baseurl = 'https://api.ip2location.io/?';
            $params = [
                'key'     => IP2LOCATION_IO_API_KEY,
                'ip'      => $ip,
                'lang'    => ((defined('IP2LOCATION_IO_LANGUAGE')) ? IP2LOCATION_IO_LANGUAGE : ''),
                'source'  => 'cakephp-ipx',
            ];
            // Remove parameters without values
            $params = array_filter($params);
            $url = $ioapi_baseurl . http_build_query($params);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);

            if (!curl_errno($ch)) {
                if (($data = json_decode($response, true)) === null) {
                    return false;
                }
                if (array_key_exists('error', $data)) {
                    throw new \Exception(__CLASS__ . ': ' . $data['error']['error_message'], $data['error']['error_code']);
                }
                return $data;
            }

            curl_close($ch);

            return false;
        } else {
            $ws = new \IP2Proxy\WebService(IP2PROXY_API_KEY, IP2PROXY_PACKAGE, IP2PROXY_USESSL);

            try {
                $records = $ws->lookup($ip);
            } catch (Exception $e) {
                return null;
            }

            return $records;
        }
    }

}

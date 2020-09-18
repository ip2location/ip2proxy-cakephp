<?php
namespace IP2ProxyCakePHP\Controller;

// Web Service Settings
if(!defined('IP2PROXY_API_KEY')) {
	define('IP2PROXY_API_KEY', 'demo');
}

if(!defined('IP2PROXY_PACKAGE')) {
	define('IP2PROXY_PACKAGE', 'PX1');
}

if(!defined('IP2PROXY_USESSL')) {
	define('IP2PROXY_USESSL', false);
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

    public function get($ip, $query = array())
    {
        $obj = new \IP2Proxy\Database();
        $obj->open(ROOT . DS . 'vendor' . DS . 'ip2location' . DS . 'ip2proxy-cakephp' . DS . 'src' . DS . 'Data' . DS . 'IP2PROXY.BIN', \IP2Proxy\Database::FILE_IO);

        try {
            $records = $obj->getAll($ip);
        } catch (Exception $e) {
            return null;
        }

        $obj->close();
        return $records;
    }

    public function getWebService($ip)
    {
        $ws = new \IP2Proxy\WebService(IP2PROXY_API_KEY, IP2PROXY_PACKAGE, IP2PROXY_USESSL);

        try {
            $records = $ws->lookup($ip);
        } catch (Exception $e) {
            return null;
        }

        return $records;
    }

}

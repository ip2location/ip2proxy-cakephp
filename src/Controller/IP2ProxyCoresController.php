<?php
namespace IP2ProxyCakePHP\Controller;

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
        $obj->open(ROOT . DS . 'vendor' . DS . 'ip2location' . DS . 'ip2proxy-cakephp' . DS . 'src' . DS . 'data' . DS . 'IP2PROXY.BIN', \IP2Proxy\Database::FILE_IO);


        try {
            $records = $obj->getAll($ip);
        } catch (Exception $e) {
            return null;
        }

        $obj->close();
        return $records;
    }

}

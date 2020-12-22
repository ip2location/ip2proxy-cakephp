<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use IP2ProxyCakePHP\Controller\IP2ProxyCoresController;

class ControllerTest extends TestCase
{
	public function testGetDb() {
		$IP2Proxy = new IP2ProxyCoresController();
		$db = './src/Data/IP2PROXY.BIN';
		$record = $IP2Proxy->get('1.0.241.135', $db);

		$this->assertEquals(
			'TH',
			$record['countryCode'],
		);
	}

	public function testGetWebService() {
		$IP2Proxy = new IP2ProxyCoresController();
		$record = $IP2Proxy->getWebService('1.0.241.135');

		$this->assertEquals(
			'TH',
			$record['countryCode'],
		);
	}
}
<?php
declare(strict_types=1);


namespace App\Service\Zotero\Updated;

use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Endpoint\Items;
use ZoteroApi\Source\UsersSource;
use ZoteroApi\ZoteroApi;

class ZoteroSyncer
{

	private ZoteroApi $api;

	public function test(): void
	{
		phpinfo();
		die();
		$apiKey = $_ENV['ZOTERO_API_KEY'];
		$userId= $_ENV['ZOTERO_USER_ID'];

		if (!$apiKey || !$userId) {
			throw new \Exception('Unable to load keys from .env');
		}

		$api = new ZoteroApi($apiKey, new UsersSource($userId));
		$api->setEndpoint(
			new Items(AbstractEndpoint::ALL)
		)->run();
		dd($api->getBody());
		dd($api);
	}



}
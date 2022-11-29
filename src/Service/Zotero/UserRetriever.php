<?php

namespace App\Service\Zotero;

use App\DataContainers\Zotero\User;
use ZoteroApi\Exceptions\ZoteroAccessDeniedException;
use ZoteroApi\Exceptions\ZoteroBadRequestException;
use ZoteroApi\Exceptions\ZoteroConnectionException;
use ZoteroApi\Exceptions\ZoteroEndpointNotFoundException;
use ZoteroApi\Source\KeysSource;
use ZoteroApi\ZoteroApi;

class UserRetriever
{

	/**
	 * Returns User DataContainer for an API key
	 *
	 * @param string $apiKey
	 * @return ?User
	 * @throws ZoteroAccessDeniedException
	 * @throws ZoteroBadRequestException
	 * @throws ZoteroConnectionException
	 * @throws ZoteroEndpointNotFoundException
	 */
	public function getUserByAPI(string $apiKey): ?User
	{
		$api = new ZoteroApi($apiKey, new KeysSource($apiKey));
		$api->run();
		return User::createFromBody($api->getBody());
	}

}
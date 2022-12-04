<?php
declare(strict_types=1);


namespace App\Service\Zotero\DataEntity;

use App\Service\Zotero\Entity\IDoctrineEntity;
use Doctrine\ORM\Mapping\Entity;

class RecordsPersistedData
{

	private static array $records = [];

	public static function addGroup(string $name) : void
	{
		self::$records[$name] = [];
	}

	public static function addRecord(string $groupName, string $recordKey, mixed $entity) : void
	{
		self::$records[$groupName][$recordKey] = $entity;
	}

	public static function getRecord(string $groupName, string $recordKey) : mixed
	{
		return self::$records[$groupName][$recordKey];
	}

	public static function doesValueExist(string $groupName, string $recordKey) : bool
	{
		return isset(self::$records[$groupName][$recordKey]);
	}

}
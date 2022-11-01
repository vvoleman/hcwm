<?php

namespace App\Service\Zotero\Factory;

use App\Service\Zotero\Entity\Collection;

class CollectionFactory
{

	public static function makeCollection(array $data): Collection
	{
		return new Collection(
			$data['key'],
			$data['data']['name'],
			$data['data']['parentCollection'],
		);
	}

	public static function makeMultipleCollections(array $multipleData): array
	{
		$collections = [];
		foreach ($multipleData as $data) {
			$collections[] = self::makeCollection($data);
		}
		return $collections;
	}

}
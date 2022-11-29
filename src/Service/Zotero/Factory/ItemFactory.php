<?php

namespace App\Service\Zotero\Factory;

use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Entity\LanguageEnum;

class ItemFactory
{

	public const FORBIDDEN = ['note'];

	public static function makeItem(array $data): Item
	{
		$isDate = isset($data['date']);
		$key = $data['key'];
		$data = $data['data'];
		return new Item(
			$key,
			$data['title'],
			$data['url'],
			AuthorFactory::makeMultipleAuthors($data['creators']),
			$data['abstractNote'],
			LanguageEnum::from($data['language']),
			TagFactory::makeMultipleTags($data['tags']),
			new \DateTimeImmutable($data['dateAdded']),
			new \DateTimeImmutable($data['dateModified']),
			$isDate ? new \DateTimeImmutable($data['date']) : null,
			$data['collections'][0] ?? null
		);
	}

	public static function makeMultipleItems(array $multipleData): array
	{
		$items = [];
		foreach ($multipleData as $data) {
			$items[] = self::makeItem($data);
		}

		return $items;
	}

}
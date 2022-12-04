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
		$data = self::validateRequiredFields($data, ['title', 'url', 'abstractNote', 'language']);
		$language = self::prepareLanguage($data);

		return new Item(
			$key,
			$data['title'],
			$data['url'],
			AuthorFactory::makeMultipleAuthors($data['creators']),
			$data['abstractNote'],
			$language,
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

	private static function prepareLanguage(array $data): LanguageEnum
	{
		if (!isset($data['language']) || $data['language'] === '' || $data['language'] === null) {
			echo "* Language is not set for item {$data['key']} ({$data['title']}), setting to default (Czech)\n";
			$language = 'cs';
		} else if (LanguageEnum::tryFrom($data['language']) === null) {
			echo "* Language {$data['language']} is not supported for item {$data['key']} ({$data['title']}), setting to default (Czech)\n";
			$language = 'cs';
		} else {
			$language = $data['language'];
		}

		return LanguageEnum::from($language);
	}

	private static function validateRequiredFields(array $data, array $fields): array
	{
		foreach ($fields as $field) {
			if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
				throw new \InvalidArgumentException("Field {$field} is required in item {$data['key']} ({$data['title']})");
			}
		}

		return $data;
	}

}
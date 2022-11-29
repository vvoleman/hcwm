<?php

namespace App\Service\Zotero\Factory;

class TagFactory
{
	public static function makeTag(array $data): string
	{
		return $data['tag'];
	}

	public static function makeMultipleTags(array $multipleData): array
	{
		$tags = [];
		foreach ($multipleData as $data) {
			$tags[] = self::makeTag($data);
		}
		return $tags;
	}
}
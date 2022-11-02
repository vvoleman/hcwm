<?php

namespace App\Tests\Service\Zotero;

use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Entity\LanguageEnum;
use PHPUnit\Framework\TestCase;

class ItemTestCase extends TestCase
{
	protected function generateItem(string $key, string $name, string $parentKey): Item
	{
		return new Item(
			$key,
			$name,
			'url',
			[],
			'',
			LanguageEnum::CS,
			[],
			new \DateTimeImmutable(),
			new \DateTimeImmutable(),
			null,
			$parentKey
		);
	}
}
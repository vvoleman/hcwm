<?php

namespace App\Tests\Service\Zotero\Factory;

use App\Service\Zotero\Factory\CollectionFactory;
use App\Service\Zotero\Factory\ItemFactory;
use PHPUnit\Framework\TestCase;

class ItemFactoryTest extends TestCase
{
	private const ID = 'DFTV3XT2';

	public function testMakeMultipleCollections()
	{
		$data = MockZoteroData::getItemData();
		$arr = [$data, $data, $data];
		$items = ItemFactory::makeMultipleItems($arr);

		$this->assertSameSize($arr, $items);
		foreach ($items as $item) {
			$this->assertSame(self::ID, $item->getKey());
		}
	}

	public function testMakeCollection()
	{
		$data = MockZoteroData::getItemData();
		$item = ItemFactory::makeItem($data);

		$this->assertSame(self::ID, $item->getKey());
	}
}

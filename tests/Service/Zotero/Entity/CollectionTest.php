<?php

namespace App\Tests\Service\Zotero\Entity;

use App\Service\Zotero\Entity\Collection;
use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Exception\Entity\DuplicateChildException;
use App\Service\Zotero\Exception\Entity\InvalidParentException;
use App\Tests\Service\Zotero\ItemTestCase;
use Iterator;
use PHPUnit\Framework\TestCase;

class CollectionTest extends ItemTestCase
{

	/**
	 * @covers \App\Service\Zotero\Entity\Collection::addItem
	 * @covers \App\Service\Zotero\Entity\Collection::getItems
	 * @dataProvider addItemProviderInvalidParent
	 */
	public function testAddItemInvalidParent(Collection $collection, Item $item, bool $isOk): void
	{
		if (!$isOk) {
			$this->expectException(InvalidParentException::class);
		}

		$collection->addItem($item);

		if ($isOk) {
			// Check if item was added
			$this->assertTrue(isset($collection->getItems()[$item->getKey()]));
		}
	}

	public function addItemProviderInvalidParent(): Iterator
	{
		yield [
			new Collection('key1', 'name1', ''),
			$this->generateItem('key2', 'name2', 'key1'),
			true,
		];

		yield [
			new Collection('key1', 'name1', ''),
			$this->generateItem('key2', 'name2', 'key3'),
			false,
		];
	}

	/**
	 * @covers \App\Service\Zotero\Entity\Collection::addItem
	 * @dataProvider addItemDuplicateProvider
	 */
	public function testAddItemDuplicate(Collection $collection, Item $item, bool $isOk): void
	{
		if ($isOk) {
			$this->expectNotToPerformAssertions();
		} else {
			$this->expectException(DuplicateChildException::class);
		}

		$collection->addItem($item);
	}

	public function addItemDuplicateProvider(): Iterator
	{
		$collection = new Collection('key1', 'name1', '');
		yield [
			$collection,
			$this->generateItem('key2', 'name2', 'key1'),
			true,
		];

		yield [
			$collection,
			$this->generateItem('key2', 'name2', 'key1'),
			false,
		];
	}
}

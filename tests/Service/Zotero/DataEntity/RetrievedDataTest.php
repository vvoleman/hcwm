<?php

namespace App\Tests\Service\Zotero\DataEntity;

use App\Service\Zotero\DataEntity\RetrievedData;
use App\Service\Zotero\Entity\Collection;
use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Entity\LanguageEnum;
use App\Service\Zotero\Exception\Entity\ZoteroEntityException;
use App\Tests\Service\Zotero\ItemTestCase;
use Iterator;
use PHPUnit\Framework\TestCase;

class RetrievedDataTest extends ItemTestCase
{

	/**
	 * @covers \App\Service\Zotero\DataEntity\RetrievedData::addItem
	 * @covers \App\Service\Zotero\DataEntity\RetrievedData::addCollection
	 * @dataProvider addCollectionProvider
	 */
	public function testAddItem(RetrievedData $retrievedData, array $collections, Item $item, bool $isOk): void
	{
		foreach ($collections as $collection) {
			$retrievedData->addCollection($collection);
		}

		if ($isOk) {
			$this->expectNotToPerformAssertions();
		} else {
			$this->expectException(ZoteroEntityException::class);
		}

		$retrievedData->addItem($item);
	}

	public function addCollectionProvider(): Iterator
	{
		$retrievedData = new RetrievedData();
		$collections = [
			new Collection('key1', 'name1', ''),
			new Collection('key2', 'name2', 'key1')
		];

		yield [
			$retrievedData,
			$collections,
			$this->generateItem('key3', 'name3', 'key2'),
			true,
		];

		yield [
			$retrievedData,
			$collections,
			$this->generateItem('key4', 'name4', 'key8'),
			false,
		];
	}

	/**
	 * @covers \App\Service\Zotero\DataEntity\RetrievedData::getSortedCollections
	 * @dataProvider getSortedCollectionsProvider
	 */
	public function testGetSortedCollections(RetrievedData $retrievedData, int $expectedCount): void
	{
		$this->assertCount($expectedCount, $retrievedData->getSortedCollections());
	}

	public function getSortedCollectionsProvider(): Iterator
	{
		$a = new RetrievedData();
		$a->addCollection(new Collection('key1', 'name1', ''));
		$a->addCollection(new Collection('key2', 'name2', ''));
		$a->addCollection(new Collection('key3', 'name3', ''));

		$b = new RetrievedData();
		$b->addCollection(new Collection('key1', 'name1', ''));
		$b->addCollection(new Collection('key2', 'name2', 'key1'));
		$b->addCollection(new Collection('key3', 'name3', 'key2'));

		yield [$a, 3];
		yield [$b, 1];
	}
}

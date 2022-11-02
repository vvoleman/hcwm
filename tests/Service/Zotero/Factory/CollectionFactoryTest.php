<?php

namespace App\Tests\Service\Zotero\Factory;

use App\Service\Zotero\Factory\CollectionFactory;
use PHPUnit\Framework\TestCase;

class CollectionFactoryTest extends TestCase
{
	private const ID = 'FEXTSB7P';

	public function testMakeMultipleCollections()
	{
		$data = MockZoteroData::getCollectionData();
		$arr = [$data, $data, $data];
		$collections = CollectionFactory::makeMultipleCollections($arr);

		$this->assertSameSize($arr, $collections);
		foreach ($collections as $collection) {
			$this->assertSame(self::ID, $collection->getKey());
		}
	}

	public function testMakeCollection()
	{
		$data = MockZoteroData::getCollectionData();
		$collection = CollectionFactory::makeCollection($data);

		$this->assertSame(self::ID, $collection->getKey());
	}
}

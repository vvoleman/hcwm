<?php
declare(strict_types=1);


namespace App\Service\Zotero;

use App\Service\Util\LoggerTrait;
use App\Service\Zotero\DataEntity\RecordsPersistedData;
use App\Service\Zotero\DataEntity\RetrievedData;
use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Entity\ZoteroEntity;
use App\Service\Zotero\Factory\CollectionFactory;
use App\Service\Zotero\Factory\ItemFactory;
use Doctrine\ORM\EntityManagerInterface;
use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Endpoint\Items;
use ZoteroApi\Source\UsersSource;
use ZoteroApi\ZoteroApi;

class ZoteroSyncer
{
	use LoggerTrait;

	private ZoteroApi $api;
	private EntityManagerInterface $manager;

	public function __construct(EntityManagerInterface $manager) {
		$this->manager = $manager;
	}

	/**
	 * @throws \Exception
	 */
	public function sync(?string $apiKey = null, ?string $userId = null): void
	{
		$apiKey = $apiKey ?? $_ENV['ZOTERO_API_KEY'];
		$userId= $userId ?? $_ENV['ZOTERO_USER_ID'];

		if (!$apiKey || !$userId) {
			throw new \Exception('Unable to load keys from .env');
		}

		$entities = $this->getAllEntities($apiKey, $userId);

		RecordsPersistedData::addGroup('tags');
		/** @var ZoteroEntity $entity */
		foreach ($entities as $entity) {
			$entity->makeDoctrineEntity($this->manager);
		}

		$this->manager->flush();
		$this->getLogger()->info('Zotero sync complete, ' . count($entities) . ' entities persisted (if not already)');
	}

	protected function getAllEntities(string $apiKey, string $userId): array
	{
		$api = new ZoteroApi($apiKey, new UsersSource($userId));

		// Make a hierarchy
		$endpoints = [
			'collections' => new Collections(AbstractEndpoint::ALL),
			'items' => new Items(AbstractEndpoint::ALL),
		];

		$retrievedData = new RetrievedData();

		$results = $api->setEndpoint($endpoints['collections'])->run()->getBody();
		foreach ($results as $result) {
			$retrievedData->addCollection(CollectionFactory::makeCollection($result));
		}

		$results = $api->setEndpoint($endpoints['items'])->run()->getBody();
		foreach ($results as $result) {
			if(in_array($result['data']['itemType'], ItemFactory::FORBIDDEN)) {
				continue;
			}
			$retrievedData->addItem(ItemFactory::makeItem($result));
		}

		return $retrievedData->getSortedCollections();
	}

	protected function sortEntities(array $entities): array
	{
		$tree = [];

		// 1. Put all items to collections
		foreach ($entities['items'] as $item) {
			/** @var Item $item */
			$entities['collections'][$item->getParentKey()]->addItem($item);
		}

		foreach ($entities['collections'] as $collection) {
			if (!$collection->getParentKey()) {
				$tree[] = $collection;
			} else {
				$entities['collections'][$collection->getParentKey()]->addItem($collection);
			}
		}

		dd($tree);
	}

}
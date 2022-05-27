<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\Collection;
use App\Entity\CollectionLanguage;
use App\Entity\Item;
use App\Entity\ItemLanguage;
use App\Exception\MissingParameterException;
use App\Repository\CollectionRepository;
use App\Repository\ItemRepository;
use App\Service\Collections\CollectionsFinder;
use App\Service\Filter\CategoryFilterModifier;
use App\Service\Filter\CollectionFilterModifier;
use App\Service\Filter\Filter;
use App\Service\Filter\LanguageFilterModifier;
use App\Service\Filter\QueryFilterModifier;
use App\Service\Items\ItemsFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/items', name: 'api_items')]
class ItemsController extends BaseApiController
{

	#[Route('/', name: '_get', methods: ['GET'])]
	public function getFilteredItems(
		Request $request,
		ItemRepository $itemRepository,
		CollectionRepository $collectionRepository,
		CollectionsFinder $finder
	): JsonResponse {
		$required = [
			'currentFolder' => false,
			'query' => false,
			'categories' => false,
			'languages' => false
		];
		try {
			$params = $this->getParams($required);
		} catch (MissingParameterException $e) {
			return $this->error($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		if (isset($params['currentFolder'])) {
			$collection = $finder->find($params['currentFolder'])['parent'];
		} else {
			$collection = null;
		}

		$isDirty = $this->isDirty($params, ['languages', 'categories', 'query']);
		$modifiers = [];
		if ($collection) {
			$modifiers[] = new CollectionFilterModifier($collection, $isDirty);
		}

		if (array_key_exists('query', $params) && strlen($params['query']) > 0) {
			$modifiers[] = new QueryFilterModifier($params['query']);
		}

		if (array_key_exists('languages', $params)) {
			$modifiers[] = new LanguageFilterModifier($params['languages']);
		}

		if (array_key_exists('categories', $params)) {
			$modifiers[] = new CategoryFilterModifier($params['categories']);
		}
		$filter = new Filter($itemRepository, $modifiers);

		if($isDirty || $collection){
			$items = $filter->run();
		} else {
			$items = [];
		}

		$dataItems = array_map(function ($item) {
			return $this->formatItem($item);
		}, $items);

		$folders = $finder->findSubcollections($isDirty, $collection, $items);

		$dataFolders = array_map(function ($folder) {
			return $this->formatFolder($folder);
		}, $folders);

		return new JsonResponse([
			'items' => $dataItems,
			'folders' => $dataFolders
		], Response::HTTP_OK);
	}

	#[Route('/recent', name: '_recent', methods: ['GET'])]
	public function getRecentItems(ItemsFinder $finder): JsonResponse
	{
		$limit = 4;

		$items = $finder->getRecentCollections($limit);

		$items = array_map(function ($item) {
			$titles = $item->getItemsLanguages()->map(function ($iL){
				/** @var ItemLanguage $iL */
				return [
					'code' => $iL->getLanguage()->getCode(),
					'text' => $iL->getText()
				];
			})->toArray();

			return [
				'id' => $item->getId(),
				'title' => $titles,
				'abstract' => $item->getAbstract(),
				'url' => $item->getUrl(),
				'categories' => array_map(function($tag){
					return [
						'id' => $tag->getId(),
						'text' => $tag->getText()
					];
				},$item->getTags())
			];
		}, $items);

		return $this->send($items);
	}

	private function formatItem(Item $item): array
	{
		return [
			'id' => $item->getId(),
			'title' => $item->getItemLanguages()->map(function ($itemLanguage) {
				/** @var ItemLanguage $itemLanguage */
				return [
					'code' => $itemLanguage->getLanguage()->getCode(),
					'text' => $itemLanguage->getText()
				];
			})->toArray(),
			'abstract' => $item->getAbstract(),
			'url' => $item->getUrl(),
			'categories' => array_map(function ($tag) {
				return [
					'id' => $tag->getId(),
					'text' => $tag->getText()
				];
			}, $item->getTags())
		];
	}

	private function formatFolder(Collection $folder): array
	{
		$texts = [];
		$paths = [];
		foreach ($folder->getCollectionsLanguages() as $collectionLanguage) {
			$code = $collectionLanguage->getLanguage()->getCode();
			$texts[] = [
				'code' => $code,
				'text' => $collectionLanguage->getText()
			];
			$paths[] = [
				'code' => $code,
				'path' => $folder->buildUrl($code)
			];
		}

		return [
			'id' => $folder->getId(),
			'text' => $texts,
			'path' => $paths
		];
	}

	private function isDirty(array $params, array $keys): bool
	{
		foreach ($keys as $key) {
			if (array_key_exists($key, $params)) {
				if (is_string($params[$key]) && strlen($params[$key]) == 0) {
					continue;
				}
				return true;
			}
		}

		return false;
	}
}
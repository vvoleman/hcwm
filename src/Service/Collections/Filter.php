<?php

declare(strict_types=1);


namespace App\Service\Collections;

use App\Entity\Collection;
use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\CollectionRepository;
use App\Repository\ItemRepository;
use App\Repository\LanguageRepository;
use App\Repository\TagRepository;
use App\Service\Template\Renderable;

class Filter extends Renderable
{

	public const ORDER_ASC = "asc";

	public const ORDER_DESC = "desc";

	private ItemRepository $itemRepository;

	private TagRepository $tagRepository;

	private LanguageRepository $languageRepository;

	/** @var string[] array */
	private array $tags;

	private string $searchQuery;

	private string $orderBy;

	/** @var string[] */
	private array $allowedLanguages;

	private int $limit;

	private int $page;

	private ?Collection $parent;

	/** @var Collection[] */
	private array $collections;

	/** @var Item[] */
	private array $items;
	private CollectionRepository $collectionRepository;

	public function __construct(
		CollectionRepository $collectionRepository,
		TagRepository $tag,
		LanguageRepository $language,
		ItemRepository $item
	) {
		$this->tagRepository = $tag;
		$this->languageRepository = $language;
		$this->itemRepository = $item;

		$this->loadParameters();
		$this->collectionRepository = $collectionRepository;
	}

	public function setProperties(Collection $parent = null, int $limit = 10, int $page = 0)
	{
		$this->parent = $parent;
		$this->limit = $limit;
		$this->page = $page;
	}

	public function getCollections(): array
	{
		if (!isset($this->collections)) {
			$this->setCollections();
		}
		return $this->collections;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function getParent(): ?Collection
	{
		return $this->parent ?? null;
	}

	private function setCollections()
	{
		if ($this->isDirty()){
			$collections = [];

			foreach ($this->items as $item) {
				if($item->getCollection()->getParent() === $this->parent){
					$collections[$item->getCollection()->getId()] = $item->getCollection();
				}
			}

			$this->collections = array_values($collections);
		} else {
			$this->collections = $this->collectionRepository->findBy([
				"parent" => $this->parent
			]);
		}
//		if (isset($this->parent)) {
//			$this->collections = $this->parent->getSubcollections()->toArray();
////			foreach ($this->items as $item) {
////				$collection = $item->getCollection();
////				if (!isset($collections[$collection->getId()])) {
////					$collections[$collection->getId()] = $item->getCollection();
////				}
////			}
////			$this->collections = array_values($collections);
//		} else {
//
//		}
	}

	/**
	 * @return Item[]
	 */
	public function run(bool $save = false): array
	{
		unset($this->collections);
		if (isset($this->page) && isset($this->limit)) {
			$page = $this->page * $this->limit;
		}

		$items = $this->itemRepository->getFiltered(
			$this->isDirty(),
			$this->tags ?? null,
			$this->allowedLanguages ?? null,
			$this->searchQuery ?? null,
			$this->parent,
			$this->limit ?? null,
			$page ?? null
		);

		if ($save) {
			$this->items = $items;
		}

		return $items;
	}

	private function loadParameters(): void
	{
		$this->tags = array_map(function ($x) {
			return intval($x);
		}, $this->clearGetParameters("tags"));

		$this->allowedLanguages = $this->clearGetParameters("languages");

		if(isset($_GET["query"]) && $_GET["query"] !== ""){
			$this->searchQuery = $_GET['query'];
		}
		$this->orderBy = $_GET["order"] ?? self::ORDER_ASC;
	}

	private function clearGetParameters(string $name){
		$arr = array_filter($_GET[$name] ?? [],function($x){
			return $x !== "";
		});

		return $arr;
	}

	/**
	 * @return Tag[]
	 */
	private function getAllTags(): array
	{
		return $this->tagRepository->findAll();
	}

	public function isDirty(): bool{
		return (isset($this->tags) && count($this->tags) > 0)
			|| (isset($this->allowedLanguages) && count($this->allowedLanguages) > 0)
			|| (isset($this->searchQuery) && $this->searchQuery !== "");
	}

	private function getAllLanguages(): array
	{
		return $this->languageRepository->findAll();
	}

	public function getTemplatePath(): string
	{
		return "partials/_filter.html.twig";
	}

	public function getTemplateVariables(): array
	{
		return [
			"tags" => [
				"all" => $this->getAllTags(),
				"selected" => $this->tags
			],
			"query" => $this->searchQuery ?? "",
			"languages" => [
				"all" => $this->getAllLanguages(),
				"selected" => $this->allowedLanguages
			],
			"directions" => [
				"all" => [
					static::ORDER_ASC => "Vzestupně",
					static::ORDER_DESC => "Sestupně"
				],
				"selected" => $this->orderBy
			],
			"result" => $this->items
		];
	}
}
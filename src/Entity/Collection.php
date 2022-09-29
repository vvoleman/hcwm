<?php

namespace App\Entity;

use App\Service\Util\NormalizeChars;
use App\Service\Zotero\PrepareLanguages;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections as Common;

#[ORM\Entity(repositoryClass: "App\Repository\CollectionRepository")]
#[ORM\Table("collections")]
class Collection
{

	#[ORM\Id]
	#[ORM\Column(type: "string")]
	private string $id;

	#[ORM\ManyToOne(targetEntity: "App\Entity\Collection", inversedBy: "subcollections")]
	#[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
	private self $parent;

	#[ORM\OneToMany(mappedBy: "parent", targetEntity: "App\Entity\Collection")]
	private Common\Collection $subcollections;

	#[ORM\OneToMany(mappedBy: "collection", targetEntity: "App\Entity\CollectionLanguage")]
	private Common\Collection $collectionsLanguages;

	#[ORM\OneToMany(mappedBy: 'collection', targetEntity: Item::class)]
	private $items;

	public function __construct()
	{
		$this->subcollections = new ArrayCollection();
		$this->collectionsLanguages = new ArrayCollection();
		$this->items = new ArrayCollection();
	}

	public function getId(): ?string
	{
		return $this->id;
	}

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

	public function getParent(): ?self
	{
		return $this->parent ?? null;
	}

	public function setParent(?self $parent): self
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * @return Common\Collection
	 */
	public function getSubcollections(): Common\Collection
	{
		return $this->subcollections;
	}

	public function addSubcollection(Collection $subcollection): self
	{
		if (!$this->subcollections->contains($subcollection)) {
			$this->subcollections[] = $subcollection;
			$subcollection->setParent($this);
		}

		return $this;
	}

	public function removeSubcollection(Collection $subcollection): self
	{
		if ($this->subcollections->removeElement($subcollection)) {
			// set the owning side to null (unless already changed)
			if ($subcollection->getParent() === $this) {
				$subcollection->setParent(null);
			}
		}

		return $this;
	}

	/**
	 * @return Common\Collection<int, CollectionLanguage>
	 */
	public function getCollectionsLanguages(): Common\Collection
	{
		return $this->collectionsLanguages;
	}

	public function getName(string $language): string
	{
		$result = $this->collectionsLanguages->filter(function ($colLang) use ($language) {
			return $colLang->getLanguage()->getCode() === $language;
		});

		if (count($result) === 0) {
			if ($language === PrepareLanguages::DEFAULT_LANGUAGE) {
				if (count($this->collectionsLanguages) === 0) {
					return "-";
				} else {
					return $this->collectionsLanguages->first()->getText();
				}
			} else {
				return $this->getName(PrepareLanguages::DEFAULT_LANGUAGE);
			}
		}

		return $result->first()->getText() ?? "";
	}

	public function buildUrl(string $language): string
	{
		if (!isset($this->parent)) {
			return NormalizeChars::normalize(str_replace(" ", "-", strtolower($this->getName($language))));
		}
		$str = $this->parent->buildUrl($language);
		$str .= "/" . NormalizeChars::normalize(str_replace(" ", "-", strtolower($this->getName($language))));

		return $str;
	}

	public function addCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
	{
		if (!$this->collectionsLanguages->contains($collectionsLanguage)) {
			$this->collectionsLanguages[] = $collectionsLanguage;
			$collectionsLanguage->setCollection($this);
		}

		return $this;
	}

	public function removeCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
	{
		if ($this->collectionsLanguages->removeElement($collectionsLanguage)) {
			// set the owning side to null (unless already changed)
			if ($collectionsLanguage->getCollection() === $this) {
				$collectionsLanguage->setCollection(null);
			}
		}

		return $this;
	}

	/**
	 * @return Common\Collection<int, Item>
	 */
	public function getItems(): Common\Collection
	{
		return $this->items;
	}

	public function addItem(Item $item): self
	{
		if (!$this->items->contains($item)) {
			$this->items[] = $item;
			$item->setCollection($this);
		}

		return $this;
	}

	public function removeItem(Item $item): self
	{
		if ($this->items->removeElement($item)) {
			// set the owning side to null (unless already changed)
			if ($item->getCollection() === $this) {
				$item->setCollection(null);
			}
		}

		return $this;
	}

	/**
	 * @return Tag[]
	 */
	public function getTags(): array
	{
		$result = [];
		/** @var Item $item */
		foreach ($this->items as $item) {
			foreach ($item->getTags() as $tag) {
				if (!array_key_exists($tag->getId(), $result)) {
					$result[$tag->getId()] = $tag;
				}
			}
		}
		return array_values($result);
	}

	/**
	 * @return Language[]
	 */
	public function getItemsLanguages(): array
	{
		$result = [];

		/** @var Item $item */
		foreach ($this->items as $item) {
			$languages = $item->getLanguages();
			foreach ($languages as $language) {
				$result[$language->getCode()] = $language;
			}
		}

		return array_values($result);
	}

	public function getBreadcrumbs(): array
	{
		$languages = ['cs', 'en', 'de'];
		$arr = [];
		$parent = $this;
		while ($parent !== null) {
			$name = [];
			$url = [];
			foreach ($languages as $language) {
				$name[$language] = $parent->getName($language);
				$url[$language] = $parent->buildUrl($language);
			}

			$arr[] = [
				'id' => $parent->getId(),
				'name' => $name,
				'url' => $url
			];
			$parent = $parent->getParent();
		}
		return array_reverse($arr);
	}

}
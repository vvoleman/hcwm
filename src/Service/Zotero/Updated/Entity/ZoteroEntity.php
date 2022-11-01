<?php

declare(strict_types=1);


namespace App\Service\Zotero\Updated\Entity;

use App\Exception\BadLanguageFormatException;
use App\Service\Zotero\PrepareLanguages;
use App\Service\Zotero\Updated\Exception\Entity\InvalidParentException;

abstract class ZoteroEntity
{

	private string $name;
	private array $localisedNames;
	private string $key;
	private ?string $parentKey;

	public function __construct(string $name, string $key, ?string $parentKey = null)
	{
		$this->name = $name;
		$this->key = $key;
		$this->parentKey = $parentKey;
	}

	/**
	 * @throws BadLanguageFormatException
	 */
	public function getNames(): array
	{
		if (!isset($this->localisedNames)) {
			$this->localisedNames = PrepareLanguages::parse($this->name);
		}

		return $this->localisedNames;
	}

	/**
	 * @return string
	 */
	public function getKey(): string
	{
		return $this->key;
	}

	/**
	 * @return ?string
	 */
	public function getParentKey(): ?string
	{
		return $this->parentKey;
	}

	public static function isChildOf(ZoteroEntity $parent, ZoteroEntity $child): bool
	{
		return $parent->getKey() === $child->getParentKey();
		if (!$silent && !$areConnected) {

		}

		return $areConnected;
	}

}
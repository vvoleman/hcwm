<?php

declare(strict_types=1);


namespace App\Service\Zotero\Updated\Entity;

use App\Exception\BadLanguageFormatException;
use App\Service\Zotero\PrepareLanguages;
use App\Service\Zotero\Updated\Exception\Entity\InvalidParentException;

abstract class ZoteroEntity implements IDoctrineEntity
{
	protected string $key;
	protected ?string $parentKey;

	public function __construct(string $key, ?string $parentKey = null)
	{
		$this->key = $key;
		$this->parentKey = $parentKey;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getParentKey(): ?string
	{
		return $this->parentKey;
	}

	public static function isChildOf(ZoteroEntity $parent, ZoteroEntity $child): bool
	{
		return $parent->getKey() === $child->getParentKey();
	}

}
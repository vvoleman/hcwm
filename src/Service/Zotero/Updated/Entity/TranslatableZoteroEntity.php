<?php

namespace App\Service\Zotero\Updated\Entity;

use App\Exception\BadLanguageFormatException;
use App\Service\Zotero\PrepareLanguages;

abstract class TranslatableZoteroEntity extends ZoteroEntity
{

	protected string $name;
	protected array $localisedNames;

	public function __construct(string $key, string $name, ?string $parentKey = null)
	{
		parent::__construct(
			$key,
			$parentKey
		);
		$this->name = $name;
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

}
<?php

namespace App\Service\Zotero\DataEntity;

use App\Entity\Language;

class TranslationData
{

	private Language $language;
	private string $text;

	public function __construct(Language $language, string $text)
	{
		$this->language = $language;
		$this->text = $text;
	}

	public function getLanguage(): Language
	{
		return $this->language;
	}

	public function getText(): string
	{
		return $this->text;
	}

}
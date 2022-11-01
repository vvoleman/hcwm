<?php

namespace App\Service\Zotero\Factory;

use App\Service\Zotero\Entity\Author;

class AuthorFactory
{
	public static function makeAuthor(array $data): Author
	{
		return new Author(
			$data['firstName'],
			$data['lastName'],
		);
	}

	public static function makeMultipleAuthors(array $multipleData): array
	{
		$authors = [];
		foreach ($multipleData as $data) {
			$authors[] = self::makeAuthor($data);
		}

		return $authors;
	}
}
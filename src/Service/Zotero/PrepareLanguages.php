<?php

namespace App\Service\Zotero;

use App\Entity\Language;
use App\Exception\BadLanguageFormatException;
use App\Exception\LanguageNotFoundException;
use App\Repository\LanguageRepository;
use App\Service\Zotero\Updated\DataEntity\TranslationData;

class PrepareLanguages
{
    public const DEFAULT_LANGUAGE = "cs";
    public const SEPARATOR = "__";

    /** @var LanguageRepository  */
    private LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository) {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param string $string
     * @return TranslationData[]
     * @throws BadLanguageFormatException
     * @throws LanguageNotFoundException
     */
    public function prepare(string $string): array
    {
		$languages = self::parse($string);

		$dict = [];

		foreach ($languages as $language) {
			$lang = $this->languageRepository->find($language['language']);
			if (!$lang) {
				throw new LanguageNotFoundException(sprintf("Couldn't find language '%s'", $language['code']));
			}

			$dict[] = new TranslationData($lang, $language['text']);
		}

        return $dict;
    }

	/**
	 * @param string $string
	 * @return array<array<string, mixed>>
	 * @throws BadLanguageFormatException
	 */
	public static function parse(string $string): array
	{
		$parts = explode(self::SEPARATOR, $string);

		// Single language
		if (sizeof($parts) == 1) {
			array_unshift($parts, self::DEFAULT_LANGUAGE);
		}

		$dict = [];
		try {
			for ($i = 0; $i < sizeof($parts) / 2; $i++) {
				$code = $parts[$i * 2];

				$dict[] = ["language" => $code, "text" => $parts[$i * 2 + 1]];
			}
		} catch (\ErrorException $e) {
			throw new BadLanguageFormatException("Exception during language processing: " . $e->getMessage());
		}

		return $dict;
	}
}
<?php

namespace App\Service\Zotero;

use App\Entity\Language;
use App\Exception\BadLanguageFormatException;
use App\Exception\LanguageNotFoundException;
use App\Repository\LanguageRepository;

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
     * @return array
     * @throws BadLanguageFormatException
     * @throws LanguageNotFoundException
     */
    public function prepare(string $string): array
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
                $lang = $this->languageRepository->find($code);

                // Couldn't find language
                if (!$lang) {
                    throw new LanguageNotFoundException(sprintf("Couldn't find language '%s'", $code));
                }

                $dict[] = ["language" => $lang, "text" => $parts[$i * 2 + 1]];
            }
        } catch (\ErrorException $e) {
            throw new BadLanguageFormatException("Exception during language processing: " . $e->getMessage());
        }

        return $dict;
    }
}
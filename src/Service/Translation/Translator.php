<?php
declare(strict_types=1);


namespace App\Service\Translation;

use App\Entity\Language;
use App\Entity\Translation;
use App\Exception\TranslatorException;
use App\Repository\TranslationRepository;
use App\Service\Translation\DeepL\DeepL;
use App\Service\Translation\DeepL\Exception\DeepLException;
use App\Service\Translation\DeepL\IDeepL;

class Translator
{

	private TranslationRepository $translationRepository;
	private IDeepL $deepL;

	public function __construct(TranslationRepository $translationRepository, DeepL $deepL) {
		$this->translationRepository = $translationRepository;
		$this->deepL = $deepL;
	}

	/**
	 * @throws TranslatorException
	 */
	public function translate(Language $from, Language $to, string $text): ?Translation
	{
		$hash = hash("sha256",$text.$to->getCode());

		// Search
		$translation = $this->translationRepository->find($hash);
		if($translation === null){
			if($from->getCode() == $to->getCode()){
				$translation = new Translation();
				$translation->setId($hash)
					->setFromLanguage($from)
					->setToLanguage($to)
					->setText($text);

				$this->translationRepository->add($translation);
				return $translation;
			}
			try{
				$translated = $this->deepL->translate($from->getCode(),$to->getCode(),$text);
				if(count($translated) == 0){
					throw new TranslatorException("No results returned");
				}
			} catch (DeepLException $e){
				throw new TranslatorException($e->getMessage());
			}

			$translation = new Translation();
			$translation->setId($hash)
				->setFromLanguage($from)
				->setToLanguage($to)
				->setText($translated[0]->getText());

			$this->translationRepository->add($translation);
		}

		return $translation;
	}

}
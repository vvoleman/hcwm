<?php
declare(strict_types=1);


namespace App\Service\Translation\DeepL\ResponseBag;

class Translation
{

	private string $detectedLanguage;
	private string $text;

	/**
	 * @param string $detectedLanguage
	 * @param string $text
	 */
	private function __construct(string $detectedLanguage, string $text)
	{
		$this->detectedLanguage = $detectedLanguage;
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getDetectedLanguage(): string
	{
		return $this->detectedLanguage;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @param array $data
	 * @return Translation[]
	 */
	public static function makeFromResponse(array $data): array{
		$result = [];
		foreach ($data["translations"] as $translation) {
			$result[] = new Translation($translation["detected_source_language"],$translation["text"]);
		}
		return $result;
	}


}
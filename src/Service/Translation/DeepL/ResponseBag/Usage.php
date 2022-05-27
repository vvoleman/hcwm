<?php
declare(strict_types=1);


namespace App\Service\Translation\DeepL\ResponseBag;

class Usage
{
	private int $characterCount;

	private int $characterLimit;

	private function __construct(int $characterCount, int $characterLimit) {
		$this->characterCount = $characterCount;
		$this->characterLimit = $characterLimit;
	}

	/**
	 * @return int
	 */
	public function getCharacterCount(): int
	{
		return $this->characterCount;
	}

	/**
	 * @return int
	 */
	public function getCharacterLimit(): int
	{
		return $this->characterLimit;
	}

	public static function makeFromRequest(array $data): Usage{
		return new Usage($data["character_count"],$data["character_limit"]);
	}

}
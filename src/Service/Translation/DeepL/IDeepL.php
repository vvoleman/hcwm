<?php
declare(strict_types=1);

namespace App\Service\Translation\DeepL;

use App\Service\Translation\DeepL\Exception\DeepLException;
use App\Service\Translation\DeepL\ResponseBag\Translation;
use App\Service\Translation\DeepL\ResponseBag\Usage;

interface IDeepL
{

	/**
	 * @param string $from
	 * @param string $to
	 * @param string $text
	 * @return Translation|Translation[]
	 * @throws DeepLException
	 */
	public function translate(string $from, string $to, string $text): Translation|array;

	public function getUsage(): Usage;
}
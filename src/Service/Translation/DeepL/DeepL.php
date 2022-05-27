<?php
declare(strict_types=1);


namespace App\Service\Translation\DeepL;

use App\Service\Translation\DeepL\Exception\DeepLException;
use App\Service\Translation\DeepL\ResponseBag\Translation;
use App\Service\Translation\DeepL\ResponseBag\Usage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class DeepL implements IDeepL
{

	private const BASE_URI = "https://api-free.deepl.com/v2";

	private string $apiKey;

	private Client $client;

	public function __construct(string $apiKey) {
		$this->apiKey = $apiKey;
	}

	private function getClient(): Client{
		if(!isset($this->client)){
			$this->client = new Client([
				'base_uri' => self::BASE_URI,
				'timeout' => 5.0,
				'headers' => [
					'Authorization' => sprintf('DeepL-Auth-Key %s',$this->apiKey)
				]
			]);
		}

		return $this->client;
	}

	/**
	 * @inheritDoc
	 */
	public function translate(string $from, string $to, string $text): Translation|array
	{
		try {
			$response = $this->getClient()->request("GET", "/v2/translate", [
				"query" => [
					"text" => $text,
					"source_lang" => strtoupper($from),
					"target_lang" => strtoupper($to)
				],
			]);
		} catch (GuzzleException $e) {
			throw new DeepLException($e->getMessage());
		}

		$body = $this->getJsonBody($response);

		return Translation::makeFromResponse($body);
	}

	public function getUsage(): Usage
	{
		try {
			$response = $this->getClient()->request("GET", "/v2/usage");
		} catch (GuzzleException $e) {
			throw new DeepLException($e->getMessage());
		}

		return Usage::makeFromRequest($this->getJsonBody($response));
	}

	private function getJsonBody(ResponseInterface $response){
		return json_decode($response->getBody()->getContents(),true);
	}
}
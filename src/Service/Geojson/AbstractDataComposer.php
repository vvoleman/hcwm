<?php

namespace App\Service\Geojson;

use JsonException;
use Symfony\Component\Filesystem\Exception\IOException;

abstract class AbstractDataComposer
{

	public const FOLDER_PATH = __DIR__.'/../../../storage/geography/composed';

	private bool $isDebug = false;

	public function setDebug(bool $debug): self
	{
		$this->isDebug = $debug;

		return $this;
	}

	protected abstract function getFilename(): string;

	protected abstract function prepareData(): array;

	/**
	 * @throws JsonException
	 * @throws IOException
	 */
	public function getData(): array
	{
		// Cached data
		if (file_exists($this->getFullPath()) && !$this->isDebug) {
			return $this->readFile();
		}

		$data = $this->prepareData();

		$this->writeFile($data);

		return $data;
	}

	/**
	 * @return array
	 * @throws IOException File not found
	 * @throws JsonException Invalid JSON format
	 */
	protected function readFile(): array
	{
		$path = $this->getFullPath();

		if (!file_exists($path)){
			throw new IOException("File {$path} not found");
		}

		$data = json_decode(file_get_contents($path), true, JSON_THROW_ON_ERROR);

		return $data;
	}

	protected function writeFile(array $data): void
	{
		$path = $this->getFullPath();

		$file = fopen($path, 'w');
		fwrite($file, json_encode($data));
		fclose($file);
	}

	private function getFullPath(): string
	{
		return self::FOLDER_PATH.'/'.$this->getFilename();
	}

}
<?php
declare(strict_types=1);


namespace App\Service\Geojson\Country;

use App\Repository\Geography\CountryRepository;
use Symfony\Component\Filesystem\Exception\IOException;

class CountryBorderDataComposer extends \App\Service\Geojson\AbstractDataComposer
{

	public const BORDERS_FOLDER = __DIR__ . '/../../../../storage/geography/country';

	private string $id;

	private CountryRepository $countryRepository;

	public function __construct(CountryRepository $countryRepository, string $id)
	{
		$this->id = $id;
		$this->countryRepository = $countryRepository;
	}


	protected function getFilename(): string
	{
		return "country_{$this->id}_border.json";
	}

	protected function prepareData(): array
	{
		$country = $this->countryRepository->find($this->id);

		$fullPath = self::BORDERS_FOLDER . "/{$country->getId()}.json";

		if (!file_exists($fullPath)) {
			throw new IOException("File '{$fullPath}' not found");
		}

		$data = json_decode(file_get_contents($fullPath), true);

		return [
			'type' => 'Feature',
			'id' => $country->getId(),
			'properties' => [
				'id' => $country->getId(),
				'name' => $country->getName()
			],
			'geometry' => $data
		];
	}
}
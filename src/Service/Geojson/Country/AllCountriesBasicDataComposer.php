<?php

namespace App\Service\Geojson\Country;

use App\Repository\Geography\CountryRepository;

class AllCountriesBasicDataComposer extends \App\Service\Geojson\AbstractDataComposer
{

	private CountryRepository $countryRepository;

	public function __construct(CountryRepository $countryRepository) {
		$this->countryRepository = $countryRepository;
	}

	protected function getFilename(): string
	{
		return 'all_countries_basic.json';
	}

	protected function prepareData(): array
	{
		$countries = $this->countryRepository->findAll();

		$results = [];

		foreach ($countries as $country) {
			$results[] = [
				'country_id' => $country->getId(),
				'name' => $country->getName(),
			];
		}

		return $results;
	}
}
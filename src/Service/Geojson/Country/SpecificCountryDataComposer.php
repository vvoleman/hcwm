<?php

namespace App\Service\Geojson\Country;

use App\Entity\Geography\Trash\TrashRecordContainer;
use App\Repository\Geography\CountryRepository;
use App\Service\Geojson\AbstractDataComposer;

class SpecificCountryDataComposer extends AbstractDataComposer
{

	private string $id;

	private CountryRepository $countryRepository;

	public function __construct(string $id, CountryRepository $countryRepository)
	{
		$this->id = $id;
		$this->countryRepository = $countryRepository;
	}


	protected function getFilename(): string
	{
		return "specific_country_{$this->id}.json";
	}

	protected function prepareData(): array
	{
		$country = $this->countryRepository->find($this->id);

		$_trashes = $country->getTrashes()->toArray();
		$trashes = TrashRecordContainer::makeFromArray($_trashes);


		$_regions = $country->getRegions();
		$regions = [];

		// Regions
		foreach ($_regions as $region) {
			$regions[] = [
				'region_id' => $region->getId(),
				'name' => $region->getName(),
			];
		}

		return [
			'country_id' => $this->id,
			'name' => $country->getName(),
			'regions' => $regions,
			'trashes' => $trashes->getFormattedArray()
		];
	}
}
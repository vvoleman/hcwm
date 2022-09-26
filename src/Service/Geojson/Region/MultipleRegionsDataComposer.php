<?php

namespace App\Service\Geojson\Region;

use App\Repository\Geography\CountryRepository;
use App\Repository\Geography\RegionRepository;

class MultipleRegionsDataComposer extends \App\Service\Geojson\AbstractDataComposer
{

	private string $countryId;
	private CountryRepository $countryRepository;
	private RegionRepository $regionRepository;

	public function __construct(string $countryId, CountryRepository $countryRepository, RegionRepository $regionRepository) {
		$this->countryId = $countryId;
		$this->countryRepository = $countryRepository;
		$this->regionRepository = $regionRepository;
	}

	protected function getFilename(): string
	{
		return "multiple_regions_for_{$this->countryId}.json";
	}

	protected function prepareData(): array
	{
		$country = $this->countryRepository->find($this->countryId);

		$results = [];
		foreach ($country->getRegions() as $region) {
			$composer = new SpecificRegionDataComposer($region->getId(), $this->regionRepository);
			$results[] = $composer->getData();
		}

		return $results;
	}
}
<?php

namespace App\Service\Geojson\District;

use App\Repository\Geography\DistrictRepository;
use App\Repository\Geography\RegionRepository;

class MultipleDistrictsDataComposer extends \App\Service\Geojson\AbstractDataComposer
{

	private string $regionId;
	private RegionRepository $regionRepository;
	private DistrictRepository $districtRepository;

	public function __construct(string $regionId, RegionRepository $regionRepository, DistrictRepository $districtRepository) {
		$this->regionId = $regionId;
		$this->regionRepository = $regionRepository;
		$this->districtRepository = $districtRepository;
	}

	protected function getFilename(): string
	{
		return "multiple_districts_for_{$this->regionId}";
	}

	protected function prepareData(): array
	{
		$region = $this->regionRepository->find($this->regionId);

		$results = [];
		foreach ($region->getDistricts() as $district) {
			$composer = new SpecificDistrictDataComposer($district->getId(), $this->districtRepository);
			$results[] = $composer->getData();
		}

		return $results;
	}
}
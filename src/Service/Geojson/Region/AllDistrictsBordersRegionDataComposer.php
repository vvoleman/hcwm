<?php

namespace App\Service\Geojson\Region;

use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\AbstractDataComposer;
use App\Service\Geojson\District\DistrictFormatTrait;

class AllDistrictsBordersRegionDataComposer extends AbstractDataComposer
{

	use DistrictFormatTrait;

	public const BORDERS_FOLDER = __DIR__.'/../../../../storage/geography/districts';

	private string $id;
	private RegionRepository $regionRepository;

	public function __construct(string $id,RegionRepository $regionRepository) {
		$this->id = $id;
		$this->regionRepository = $regionRepository;
	}

	protected function getFilename(): string
	{
		return "all_districts_borders_region_{$this->id}.json";
	}

	protected function prepareData(): array
	{
		$region = $this->regionRepository->find($this->id);

		$results = [];
		foreach ($region->getDistricts() as $district) {
			$results[] = $this->formatDistrict($district);
		}

		return $results;
	}
}
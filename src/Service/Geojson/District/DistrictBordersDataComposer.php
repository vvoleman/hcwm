<?php

namespace App\Service\Geojson\District;

use App\Repository\Geography\DistrictRepository;
use App\Service\Geojson\AbstractDataComposer;

class DistrictBordersDataComposer extends AbstractDataComposer
{

	use DistrictFormatTrait;

	public const BORDERS_FOLDER = __DIR__.'/../../../../storage/geography/districts';

	private ?string $id = null;

	private DistrictRepository $districtRepository;

	public function __construct(DistrictRepository $districtRepository, ?string $id = null)
	{
		$this->id = $id;
		$this->districtRepository = $districtRepository;
	}


	protected function getFilename(): string
	{
		$part = '';
		if(!!$this->id){
			$part = "_{$this->id}";
		}

		return "district{$part}_border.json";
	}

	protected function prepareData(): array
	{
		if ($this->id) {
			return $this->prepareSingle();
		} else {
			return $this->prepareAll();
		}
	}

	private function prepareAll(): array
	{
		$regions = $this->districtRepository->findAll();

		$results = [];
		foreach ($regions as $region) {
			$results[] = $this->formatDistrict($region);
		}

		return $results;
	}

	private function prepareSingle(): array
	{
		$region = $this->districtRepository->find($this->id);

		return $this->formatDistrict($region);
	}
}
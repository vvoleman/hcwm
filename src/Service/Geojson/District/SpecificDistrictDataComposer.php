<?php

namespace App\Service\Geojson\District;

use App\Entity\Geography\Trash\TrashRecordContainer;
use App\Repository\Geography\DistrictRepository;
use App\Service\Geojson\AbstractDataComposer;

class SpecificDistrictDataComposer extends AbstractDataComposer
{

	private string $id;
	private DistrictRepository $districtRepository;

	public function __construct(string $id, DistrictRepository $districtRepository) {
		$this->id = $id;
		$this->districtRepository = $districtRepository;
	}

	protected function getFilename(): string
	{
		return "specific_region_{$this->id}.json";
	}

	protected function prepareData(): array
	{
		$district = $this->districtRepository->find($this->id);

		$trashes = TrashRecordContainer::makeFromArray($district->getTrashes()->toArray());

		return [
			'district_id' => $district->getId(),
			'name' => $district->getName(),
			'region' => $district->getRegion()->getId(),
			'trashes' => $trashes->getFormattedArray(),
			'center' => [
				'latitude' => $district->getLatitude(),
				'longitude' => $district->getLongitude()
			]
		];
	}
}
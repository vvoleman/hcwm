<?php

namespace App\Service\Geojson\Region;

use App\Entity\Geography\Trash\TrashRecordContainer;
use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\AbstractDataComposer;

class SpecificRegionDataComposer extends AbstractDataComposer
{

	private string $id;
	private RegionRepository $regionRepository;

	public function __construct(string $id, RegionRepository $regionRepository) {
		$this->id = $id;
		$this->regionRepository = $regionRepository;
	}

	protected function getFilename(): string
	{
		return "specific_region_{$this->id}.json";
	}

	protected function prepareData(): array
	{
		$region = $this->regionRepository->find($this->id);
		$trashes = TrashRecordContainer::makeFromArray($region->getTrashes()->toArray());

		return [
			'region_id' => $region->getId(),
			'name' => $region->getName(),
			'country' => $region->getCountry()->getId(),
			'trashes' => $trashes->getFormattedArray(),
			'center' => [
				'latitude' => $region->getLatitude(),
				'longitude' => $region->getLongitude()
			]
		];
	}
}
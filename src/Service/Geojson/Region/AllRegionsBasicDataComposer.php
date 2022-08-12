<?php

namespace App\Service\Geojson\Region;

use App\Entity\Geography\Region;
use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\AbstractDataComposer;

class AllRegionsBasicDataComposer extends AbstractDataComposer
{

	private RegionRepository $regionRepository;

	public function __construct(RegionRepository $regionRepository) {
		$this->regionRepository = $regionRepository;
	}

	protected function getFilename(): string
	{
		return 'all_regions_basic.geojson';
	}

	protected function prepareData(): array
	{
		$regions = $this->regionRepository->findAll();

		$results = [];

		foreach ($regions as $region) {
			$results[] = [
				'type' => 'Feature',
				'id' => $region->getId(),
				'properties' => [
					'region_id' => $region->getId(),
					'name' => $region->getName()
				],
			];
		}

		return $results;
	}
}
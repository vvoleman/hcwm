<?php

namespace App\Service\Geojson\Region;

use App\Entity\Geography\Region;
use App\Repository\Geography\RegionRepository;
use Symfony\Component\Filesystem\Exception\IOException;

class RegionBordersDataComposer extends \App\Service\Geojson\AbstractDataComposer
{

	public const BORDERS_FOLDER = __DIR__ . '/../../../../storage/geography/regions';

	private ?string $id = null;

	private RegionRepository $regionRepository;

	public function __construct(RegionRepository $regionRepository, ?string $id = null)
	{
		$this->id = $id;
		$this->regionRepository = $regionRepository;
	}


	protected function getFilename(): string
	{
		$part = '';
		if (!!$this->id) {
			$part = "_{$this->id}";
		}

		return "region{$part}_border.json";
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
		$regions = $this->regionRepository->findAll();

		$results = [];
		foreach ($regions as $region) {
			$results[] = $this->formatRegion($region);
		}

		return $results;
	}

	private function prepareSingle(): array
	{
		$region = $this->regionRepository->find($this->id);

		return $this->formatRegion($region);
	}

	private function formatRegion(Region $region): array
	{
		$fullPath = self::BORDERS_FOLDER . "/{$region->getId()}.json";

		if (!file_exists($fullPath)) {
			throw new IOException("File '{$fullPath}' not found");
		}

		$data = json_decode(file_get_contents($fullPath), true);

		return [
			'type' => 'Feature',
			'id' => $region->getId(),
			'properties' => [
				'id' => $region->getId(),
				'name' => $region->getName(),
				'coords' => [
					'latitude' => $region->getLatitude(),
					'longitude' => $region->getLongitude(),
				]
			],
			'geometry' => $data
		];
	}
}
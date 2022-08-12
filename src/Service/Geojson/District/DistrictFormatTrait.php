<?php

namespace App\Service\Geojson\District;

use App\Entity\Geography\District;
use Symfony\Component\Filesystem\Exception\IOException;

trait DistrictFormatTrait
{

	protected function formatDistrict(District $district): array
	{
		$fullPath = static::BORDERS_FOLDER."/{$district->getRegion()->getId()}/{$district->getId()}.json";

		if (!file_exists($fullPath)) {
			throw new IOException("File '{$fullPath}' not found");
		}

		$data = json_decode(file_get_contents($fullPath), true);

		return [
			'type' => 'Feature',
			'id' => $district->getId(),
			'properties' => [],
			'geometry' => $data
		];
	}

}
<?php
declare(strict_types=1);


namespace App\Service\Statistic;

use App\Exception\StatisticException;
use Symfony\Component\Finder\SplFileInfo;

class FindData
{

	public const DATA_FOLDER = __DIR__."/../../../storage/statistic";

	/**
	 * @throws StatisticException
	 */
	public function getTrashFractionByDistrict(string $id): array
	{
		// Find file
		$expectedPath = sprintf('%s/%s/stats.json',self::DATA_FOLDER, "byDistrict");
		if (file_exists($expectedPath)){
			$data = file_get_contents($expectedPath);
			$json = json_decode($data, true);

			if($json === null && json_last_error() !== JSON_ERROR_NONE){
				throw new StatisticException('Invalid data format of %s',realpath($expectedPath));
			}

			if(!isset($json[$id])){
				throw new StatisticException(sprintf('District %s not found!',$id));
			}

			return $json[$id];
		} else {
			throw new StatisticException(sprintf('Unable to find data for district with ID %d (%s)',$id, realpath($expectedPath)));
		}
	}

	/**
	 * @throws StatisticException
	 */
	public function getAllDistricts(): array
	{
		$expectedPath = sprintf('%s/%s/allDistricts.json',self::DATA_FOLDER, "byDistrict");

		return $this->loadData($expectedPath);
	}

	/**
	 * @throws StatisticException
	 */
	public function getRegionsData(): array
	{
		$expectedPath = sprintf('%s/%s/regions_ready.geojson',self::DATA_FOLDER, "regionsData");
		return $this->loadData($expectedPath);
	}

	private function loadData(string $path): array
	{
		if (file_exists($path)){
			$data = file_get_contents($path);
			$json = json_decode($data, true);

			if($json === null && json_last_error() !== JSON_ERROR_NONE){
				throw new StatisticException('Invalid data format of %s',realpath($path));
			}

			return $json;
		} else {
			throw new StatisticException(sprintf('Unable to find data (%s)', realpath($path)));
		}
	}
}
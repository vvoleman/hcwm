<?php

declare(strict_types=1);


namespace App\Service\Statistic\Excel\DataHandler;

use App\Repository\Geography\CountryRepository;
use App\Repository\Geography\DistrictRepository;
use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\District\MultipleDistrictsDataComposer;
use App\Service\Geojson\Region\MultipleRegionsDataComposer;

class TrashesByRegionsDataHandler implements IExcelDataHandler
{

	private string $type;
	private string $id;
	private int $year;
	private RegionRepository $regionRepository;
	private CountryRepository $countryRepository;
	private DistrictRepository $districtRepository;

	public function __construct(
		RegionRepository $regionRepository,
		CountryRepository $countryRepository,
		DistrictRepository $districtRepository
	) {
		$this->regionRepository = $regionRepository;
		$this->countryRepository = $countryRepository;
		$this->districtRepository = $districtRepository;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function setId(string $id): void
	{
		$this->id = $id;
	}

	public function setYear(int $year): void
	{
		$this->year = $year;
	}

	public function handleData(): array|false
	{
		$composer = match ($this->type) {
			'country' => new MultipleRegionsDataComposer($this->id, $this->countryRepository, $this->regionRepository),
			'region' => new MultipleDistrictsDataComposer($this->id, $this->regionRepository, $this->districtRepository),
			default => null
		};

		$data = $composer->getData();
		$name = match ($this->type) {
			'country' => $this->countryRepository->find($this->id)->getName(),
			'region' => $this->regionRepository->find($this->id)->getName(),
			default => null
		};

		$geographies = [];
		foreach ($data as $geography) {
			$trashes = [];
			foreach ($geography['trashes'] as $key => $years) {
				$trashes[$key] = $years[$this->year];
			}
			$geographies[] = [
				'name' => $geography['name'],
				'trashes' => $trashes
			];
		}

		return [
			'geographyLevelName' => $name,
			'geographies' => $geographies,
			'year' => $this->year
		];
	}

}
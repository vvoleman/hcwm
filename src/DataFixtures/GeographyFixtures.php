<?php

namespace App\DataFixtures;

use App\Entity\Geography\Country;
use App\Entity\Geography\District;
use App\Entity\Geography\Region;
use App\Service\Statistic\FindData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GeographyFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$regions = $this->makeCountriesAndRegions($manager);

		$this->makeDistricts($manager, $regions);

		$manager->flush();
	}

	private function makeCountriesAndRegions(ObjectManager $manager): array
	{
		$countries = [
			'cz' => [
				'name' => 'Česká republika',
				'regions' => json_decode(file_get_contents(__DIR__ . '/../../data_geography/regions.json'), true)
			]
		];
		$regions = [];
		foreach ($countries as $key => $country) {
			$countryEntity = (new Country($key))
				->setName($country['name']);
			$manager->persist($countryEntity);

			foreach ($country['regions'] as $code => $region) {
				$regionEntity = (new Region($code))
					->setName($region['name'])
					->setLatitude($region['latitude'])
					->setLongitude($region['longitude'])
					->setCountry($countryEntity);

				$regions[$code] = $regionEntity;

				$manager->persist($regionEntity);
			}
		}
		return $regions;
	}

	private function makeDistricts(ObjectManager $manager, array $regions): void
	{
		$_districts = json_decode(file_get_contents(__DIR__ . '/../../data_geography/districts_by_region.json'), true);

		foreach ($_districts as $key => $districts) {
			foreach ($districts as $code => $district) {
				$districtEntity = (new District($code))
					->setName($district['name'])
					->setRegion($regions[$key])
					->setLatitude($district['latitude'])
					->setLongitude($district['longitude']);

				$manager->persist($districtEntity);
			}
		}
	}
}

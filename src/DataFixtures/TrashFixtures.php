<?php

namespace App\DataFixtures;

use App\Entity\Geography\Country;
use App\Entity\Geography\District;
use App\Entity\Geography\Region;
use App\Entity\Geography\Trash\TrashRecordCountry;
use App\Entity\Geography\Trash\TrashRecordDistrict;
use App\Entity\Geography\Trash\TrashRecordRegion;
use App\Entity\Geography\Trash\TrashType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;

class TrashFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$types = $this->makeTypes($manager);
		dump("Types");
		$this->makeDistrictTrash($manager, $types);
		dump("District");
		$this->makeRegionTrash($manager, $types);
		dump("Region");
		$this->makeCountryTrash($manager, $types);
		dump("Country");
		$manager->flush();
	}

	private function makeTypes(ObjectManager $manager): array
	{
		$types = json_decode(file_get_contents(__DIR__ . '/../../data_geography/trash_types.json'), true);
		$entities = [];
		foreach ($types as $key => $desc) {
			$entity = (new TrashType($key))
				->setDescription($desc);

			$entities[$key] = $entity;

			$manager->persist($entity);
		}

		return $entities;
	}

	private function makeDistrictTrash(ObjectManager $manager, array $allTypes): void
	{
		$districtRepository = $manager->getRepository(District::class);
		$allDistricts = $this->reformat($districtRepository->findAll());

		$data = json_decode(file_get_contents(__DIR__ . '/../../data_geography/trash/districts_trashes.json'), true);

		foreach ($data as $districtId => $district) {
			foreach ($district as $trashType => $trashes) {
				foreach ($trashes as $year => $value) {
					$record = (new TrashRecordDistrict())
						->setDistrict($allDistricts[$districtId])
						->setTrashType($allTypes[$trashType])
						->setYear($year)
						->setValue($value ?? 0);

					$manager->persist($record);
				}
			}
		}
	}

	private function makeRegionTrash(ObjectManager $manager, array $allTypes): void
	{
		$repository = $manager->getRepository(Region::class);
		$allRegions = $this->reformat($repository->findAll());

		$data = json_decode(file_get_contents(__DIR__ . '/../../data_geography/trash/regions_trashes.json'), true);

		foreach ($data as $regionId => $types) {
			foreach ($types as $keyType => $trashes) {
				foreach ($trashes as $year => $value) {
					$record = (new TrashRecordRegion())
						->setRegion($allRegions[$regionId])
						->setTrashType($allTypes[$keyType])
						->setYear($year)
						->setValue($value ?? 0);

					$manager->persist($record);
				}
			}
		}
	}

	private function makeCountryTrash(ObjectManager $manager, array $allTypes): void
	{
		$repository = $manager->getRepository(Country::class);
		$allCountries = $this->reformat($repository->findAll());

		$data = json_decode(file_get_contents(__DIR__ . '/../../data_geography/trash/countries_trashes.json'), true);
		foreach ($data as $countryId => $types) {
			foreach ($types as $keyType => $trashes) {
				foreach ($trashes as $year => $value) {
					$record = (new TrashRecordCountry())
						->setCountry($allCountries[$countryId])
						->setTrashType($allTypes[$keyType])
						->setYear($year)
						->setValue($value ?? 0);

					$manager->persist($record);
				}
			}
		}
	}

	private function reformat(array $entities): array
	{
		$new = [];
		foreach ($entities as $entity) {
			$new[$entity->getId()] = $entity;
		}

		return $new;
	}

}

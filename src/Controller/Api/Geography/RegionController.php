<?php

namespace App\Controller\Api\Geography;

use App\Controller\BaseApiController;
use App\Repository\Geography\CountryRepository;
use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\Region\MultipleRegionsDataComposer;
use App\Service\Geojson\Region\RegionBordersDataComposer;
use App\Service\Geojson\Region\SpecificRegionDataComposer;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/geography/region', name: 'api_geography_region')]
class RegionController extends BaseApiController
{

	#[Route('/borders', name: '_borders')]
	public function allBorders(RegionBordersDataComposer $composer): JsonResponse
	{
		$composer->setDebug(true);
		return $this->handleReturn($composer);
	}

	#[Route('/parent/{countryId}', name:'_parent')]
	public function multipleGet(string $countryId, CountryRepository $countryRepository, RegionRepository $regionRepository): JsonResponse
	{
		if($countryRepository->count(['id' => $countryId]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $countryId]);
		}

		$composer = new MultipleRegionsDataComposer($countryId, $countryRepository, $regionRepository);

		return $this->send($composer->getData());
	}

	#[Route('/{id}',name: '_get')]
	public function get(string $id, RegionRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new SpecificRegionDataComposer($id, $repository);
		$data = $composer->getData();

		return $this->send($data);
	}

	#[Route('/{id}/borders', name: '_get_borders')]
	public function singleBorders(string $id, RegionRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new RegionBordersDataComposer($repository, $id);

		return $this->handleReturn($composer);
	}

	private function handleReturn(RegionBordersDataComposer $composer): JsonResponse
	{
		try {
			$data = $composer->getData();
		} catch (IOException $e) {
			return $this->error('Unable to access file', Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\JsonException $e) {
			return $this->error('There was a problem with file', Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Exception $e) {
			return $this->error('Unable to retrieve data', Response::HTTP_INTERNAL_SERVER_ERROR, [
				'error' => $e->getMessage(),
				'stacktrace' => $e->getTrace()
			]);
		}

		return $this->send($data);
	}

}
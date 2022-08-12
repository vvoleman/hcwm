<?php

namespace App\Controller\Api\Geography;

use App\Controller\BaseApiController;
use App\Repository\Geography\DistrictRepository;
use App\Repository\Geography\RegionRepository;
use App\Service\Geojson\AbstractDataComposer;
use App\Service\Geojson\District\DistrictBordersDataComposer;
use App\Service\Geojson\District\SpecificDistrictDataComposer;
use App\Service\Geojson\Region\AllDistrictsBordersRegionDataComposer;
use Exception;
use JsonException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/geography/district', name: 'api_geography_district')]
class DistrictController extends BaseApiController
{

	#[Route('/borders', name: '_borders')]
	public function allBorders(DistrictBordersDataComposer $composer): JsonResponse
	{
		return $this->handleReturn($composer);
	}

	#[Route('/borders/{id}', name: '_borders')]
	public function allBordersForRegion(string $id, RegionRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid Region ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new AllDistrictsBordersRegionDataComposer($id, $repository);

		return $this->handleReturn($composer);
	}

	#[Route('/{id}', name: '_get')]
	public function get(string $id, DistrictRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new SpecificDistrictDataComposer($id, $repository);

		return $this->send($composer->getData());
	}

	#[Route('/{id}/borders', name: '_get_borders')]
	public function singleBorders(string $id, DistrictRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new DistrictBordersDataComposer($repository, $id);

		return $this->handleReturn($composer);
	}

	private function handleReturn(AbstractDataComposer $composer): JsonResponse
	{
		try {
			$data = $composer->getData();
		} catch (IOException) {
			return $this->error('Unable to access file', Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (JsonException) {
			return $this->error('There was a problem with file', Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (Exception) {
			return $this->error('Unable to retrieve data', Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return $this->send($data);
	}

}
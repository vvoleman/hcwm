<?php

namespace App\Controller\Api\Geography;

use App\Controller\BaseApiController;
use App\Repository\Geography\CountryRepository;
use App\Service\Geojson\Country\AllCountriesBasicDataComposer;
use App\Service\Geojson\Country\CountryBorderDataComposer;
use App\Service\Geojson\Country\SpecificCountryDataComposer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/geography/country', name: 'api_geography_country')]
class CountryController extends BaseApiController
{

	#[Route('', name: '_index')]
	public function index(AllCountriesBasicDataComposer $composer): JsonResponse
	{
		$data = $composer->getData();
		return $this->send($data);
	}

	#[Route('/{id}', name: '_get')]
	public function get(string $id, CountryRepository $repository, bool $isDebug): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new SpecificCountryDataComposer($id, $repository);
		$composer->setDebug($isDebug);
		$data = $composer->getData();
		return $this->send($data);
	}

	#[Route('/{id}/borders', name: '_borders')]
	public function borders(string $id, CountryRepository $repository): JsonResponse
	{
		if($repository->count(['id' => $id]) === 0) {
			return $this->error("Invalid ID", Response::HTTP_NOT_FOUND, ['id' => $id]);
		}

		$composer = new CountryBorderDataComposer($repository, $id);

		return $this->send($composer->getData());
	}

}

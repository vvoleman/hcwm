<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\Exception\StatisticException;
use App\Service\Statistic\FindData;
use App\Service\Zotero\LoadZoteroCollections;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZoteroApi\Source\UsersSource;

#[Route('/statistic',name:'api_statistic')]
class StatisticController extends BaseApiController
{

	#[Route("/getTrashByDistrict",name:"by_district",methods: ["GET"])]
	public function getByDistrict(FindData $findData, LoadZoteroCollections $collections): Response{
		$storagePath = __DIR__.'/../../../storage';
		$data = json_decode(file_get_contents($storagePath.'/kraje.geojson'), true);
		$features = $data['features'];
		foreach ($features as $feature) {
			$code = $feature['properties']['KOD_NUTS3'];
			$code = str_split($code);
			$code[2] = '-';
			$code = join('',$code);

			$file = fopen($storagePath."/geography/regions/{$code}.json", 'w');
			fwrite($file, json_encode($feature['geometry']));
			fclose($file);
		}
		dd($features);


		$required = ['district_id'=>true];

		try {
			$params = $this->getParams($required);
		} catch (MissingParameterException $e){
			return $this->error($e->getMessage(),Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		if(!is_numeric($params['district_id'])){
			return $this->error('District id has to be integer!');
		}

		try {
			$data = $findData->getTrashFractionByDistrict($params['district_id']);
		} catch (StatisticException $e) {
			return $this->error($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
		}



		return $this->send($data);
	}

//	#[Route("/getTrashByDistrict",name:"by_district",methods: ["GET"])]
//	public function getByRegion(FindData $findData): Response{
//		$required = ['district_id'=>true];
//
//		try {
//			$params = $this->getParams($required);
//		} catch (MissingParameterException $e){
//			return $this->error($e->getMessage(),Response::HTTP_UNPROCESSABLE_ENTITY);
//		}
//
//		if(!is_numeric($params['district_id'])){
//			return $this->error('District id has to be integer!');
//		}
//
//		try {
//			$regions = $findData->getRegionsData();
//			dump($regions);
//			$data = $findData->getRegionsData($params['district_id']);
//		} catch (StatisticException $e) {
//			return $this->error($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
//		}
//
//
//
//		return $this->send($data);
//	}

	#[Route('/getRegionsData', name:'_regions_data')]
	public function getRegionsData(FindData $findData): JsonResponse
	{
		try {
			$data = $findData->getRegionsData();
		} catch (StatisticException $e) {
			return $this->error($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return $this->send($data);
	}

	#[Route('/getRegionDetails', name: '_region_details')]
	public function getRegionDetails(FindData $findData): JsonResponse
	{
		$required = ['region_id'=>true];

		try {
			$params = $this->getParams($required);
		} catch (MissingParameterException $e){
			return $this->error($e->getMessage(),Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		try {
			$data = $findData->getRegionDetails($params['region_id']);

			return $this->send($data);
		} catch (StatisticException $e) {
			return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	#[Route('/getCountryData', name: '_country_data')]
	public function getCountryData(FindData $findData): JsonResponse
	{
		try {
			$data = $findData->getCountryData();
		} catch (StatisticException $e) {
			return $this->error($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return $this->send($data);
	}
}

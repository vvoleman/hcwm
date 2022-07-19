<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\Exception\StatisticException;
use App\Service\Statistic\FindData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/statistic',name:'api_statistic')]
class StatisticController extends BaseApiController
{

	#[Route("/getTrashByDistrict",name:"by_district",methods: ["GET"])]
	public function getByDistrict(FindData $findData): Response{
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

}
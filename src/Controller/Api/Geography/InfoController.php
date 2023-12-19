<?php

namespace App\Controller\Api\Geography;

use App\Controller\BaseApiController;
use App\Entity\Geography\Trash\TrashRecordContainer;
use App\Repository\Geography\Trash\TrashTypeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/info/trashes', name: 'api_info_trashes')]
class InfoController extends BaseApiController
{

	#[Route('/', name: '')]
	public function index(TrashTypeRepository $repository): JsonResponse
	{
		$filtered = TrashRecordContainer::filterTypes($repository->findAll());

		$colors = $this->getColors();

		$results = ['allowed'=>[],'others'=>[], 'colors' => $colors];

		foreach ($filtered as $key => $arr) {
			foreach ($arr as $item) {
				$results[$key][$item->getId()] = $item->getDescription();
			}
		}

		return $this->send($results);
	}

	private function getColors(): array
	{
		$name = __DIR__.'/../../../../storage/statistic/colors.json';

		if (!file_exists($name)) {
			return [];
		}

		$file = file_get_contents($name);

		return json_decode($file, true);
	}

}

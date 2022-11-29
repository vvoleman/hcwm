<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\Language;
use App\Repository\LanguageRepository;
use App\Repository\TagRepository;
use App\Service\Statistic\FindData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api/assets', name: 'api_assets')]
class AssetsController extends BaseApiController
{



	#[Route('/categories', name: '_categories', methods: ['GET'])]
	public function getCategories(TagRepository $tagRepository): JsonResponse
	{
		$tags = array_map(function ($tag) {
			return [
				'id' => $tag->getId(),
				'text' => $tag->getText()
			];
		}, $tagRepository->findAll());

		return $this->send($tags);
	}

	#[Route('/languages', name: '_languages', methods: ['GET'])]
	public function getLanguages(LanguageRepository $languageRepository): JsonResponse
	{
		$url = $this->generateUrl('app', [], UrlGeneratorInterface::ABSOLUTE_URL);
		$languages = array_map(function ($language) use($url){
			/** @var Language $language */
			return [
				'code' => $language->getCode(),
				'name' => $language->getName(),
				'flag' => sprintf('%s%s',$url, $language->getFlag())
			];

		}, $languageRepository->findAll());



		return $this->send($languages);
	}

	#[Route('/districts', name: '_districts', methods: ['GET'])]
	public function getDistricts(FindData $findData)
	{
		$data = $findData->getAllDistricts();

		return $this->send($data);
	}

}
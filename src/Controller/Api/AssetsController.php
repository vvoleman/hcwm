<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\Language;
use App\Repository\LanguageRepository;
use App\Repository\TagRepository;
use App\Service\Statistic\FindData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/assets', name: 'api_assets')]
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

	#[Route('/images/{path}', name: '_images', requirements: ['path' => '.+'], methods: ['GET'])]
	public function getImage(string $path): Response {
		// Search for the file in the public directory
		$publicDirectory = $this->getParameter('kernel.project_dir') . '/public/';
		$filePath = $publicDirectory . $path;

		// Check if file exists
		if (!file_exists($filePath)) {
			throw new HttpException(Response::HTTP_NOT_FOUND, sprintf('File %s not found', $path));
		}

		// Return image as file response
		return $this->file($filePath);
	}

	#[Route('/languages', name: '_languages', methods: ['GET'])]
	public function getLanguages(LanguageRepository $languageRepository): JsonResponse
	{
		$baseUrl = $_ENV['APP_URL'] ?? '';
		$languages = array_map(function ($language) use($baseUrl){
			/** @var Language $language */
			return [
				'code' => $language->getCode(),
				'name' => $language->getName(),
				'flag' => sprintf('%s/%s',$baseUrl, $language->getFlag())
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

<?php

declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Repository\Geography\CountryRepository;
use App\Repository\Geography\RegionRepository;
use App\Service\Statistic\Excel\DataHandler\TrashesByGeographyDataHandler;
use App\Service\Statistic\Excel\DataHandler\TrashesByRegionsDataHandler;
use App\Service\Statistic\Excel\ExcelProcessor;
use App\Service\Statistic\Excel\Template\TrashesByGeographyTemplate;
use App\Service\Statistic\Excel\Template\TrashesByRegionsTemplate;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/spreadsheet', name: 'api_spreadsheet', methods: ['GET'])]
class SpreadsheetController extends BaseApiController
{

	#[Route('/trashByRegions/{type}/{countryId}/{year}', name: '_regions')]
	public function trashByRegions(
		string $type,
		string $countryId,
		int $year,
		TrashesByRegionsTemplate $template,
		TrashesByRegionsDataHandler $dataHandler,
		ExcelProcessor $processor
	) {
		$dataHandler->setType($type);
		$dataHandler->setId($countryId);
		$dataHandler->setYear($year);
		$template->setYear($year);

		$processor->setDataHandler($dataHandler);
		$processor->setTemplate($template);

		try {
			$file = $processor->process();
		} catch (Exception $e) {
			return $this->error('Unable to generate spreadsheet', 500);
		}

		// get splfileinfo object
		$fileObj = new \SplFileInfo($file);
		return new BinaryFileResponse($fileObj);
	}

	#[Route('/trashByGeography/{type}/{id}', name: '_geography')]
	public function trashByGeography(
		string $type,
		string $id,
		TrashesByGeographyTemplate $template,
		TrashesByGeographyDataHandler $dataHandler,
		ExcelProcessor $processor
	) {
		$dataHandler->setType($type);
		$dataHandler->setId($id);

		$processor->setDataHandler($dataHandler);
		$processor->setTemplate($template);

		try {
			$file = $processor->process();
		} catch (Exception $e) {
			return $this->error('Unable to generate spreadsheet', 500);
		}

		// get splfileinfo object
		$fileObj = new \SplFileInfo($file);
		return new BinaryFileResponse($fileObj);
	}

}

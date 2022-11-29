<?php
declare(strict_types=1);


namespace App\Service\Statistic\Excel\Template;

use App\Service\Statistic\Excel\DataHandler\IExcelDataHandler;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface IExcelTemplate
{

	public const TEMPLATE_STORAGE = __DIR__.'/../../../../../storage/statistic/excel';

	public function getId(): string;

	public function getFileExtension(): string;

	public function applyData(Spreadsheet $spreadsheet, IExcelDataHandler $handler);

	public function setSpreadsheetDescription(Spreadsheet $spreadsheet): void;

	/**
	 * Returns durability of template (in seconds)
	 *
	 * @return int
	 */
	public function getTTL(): int;

}
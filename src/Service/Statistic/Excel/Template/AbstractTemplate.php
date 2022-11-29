<?php
declare(strict_types=1);


namespace App\Service\Statistic\Excel\Template;

use App\Service\Statistic\Excel\DataHandler\IExcelDataHandler;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class AbstractTemplate implements IExcelTemplate
{

	public function getFileExtension(): string
	{
		return 'xlsx';
	}

	public function applyData(Spreadsheet $spreadsheet, IExcelDataHandler $handler)
	{
		$data = $this->validate($handler);
		if ($data === null) {
			throw new \RuntimeException(sprintf('DataHandler %s is not valid for template %s', get_class($handler), $this->getId()));
		}

		$this->apply($spreadsheet->getActiveSheet(), $data);
	}

	private function validate(IExcelDataHandler $handler): array|null
	{
		$data = $handler->handleData();

		$fields = $this->getRequiredFields();
		foreach ($fields as $key => $value) {
			if(!isset($data[$key])) {
				return null;
			}
			if (gettype($data[$key]) !== $value && !$data[$key] instanceof $value) {
				return null;
			}
		}

		return $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getTTL(): int
	{
		return 60*60*24*30;
	}

	protected abstract function apply(Worksheet $spreadsheet, array $data): void;

	/**
	 * @return array<string, string>
	 */
	protected abstract function getRequiredFields(): array;
}
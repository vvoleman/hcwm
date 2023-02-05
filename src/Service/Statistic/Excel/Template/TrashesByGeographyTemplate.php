<?php

declare(strict_types=1);


namespace App\Service\Statistic\Excel\Template;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrashesByGeographyTemplate extends AbstractTemplate
{

	private string $place;

	public function setPlace(string $place): void
	{
		$this->place = $place;
	}

	public function getId(): string
	{
		return 'TrashByGeography';
	}

	public function getTTL(): int
	{
		// 1 week
		return 0;
		return 60 * 60 * 24 * 7;
	}

	public function getFileExtension(): string
	{
		return 'xlsx';
	}

	public function setSpreadsheetDescription(Spreadsheet $spreadsheet): void
	{
		$spreadsheet->getProperties()
			->setCreator("HCWM")
			->setLastModifiedBy("HCWM")
			->setTitle("Produkce zdravotnického odpadu v ".$this->place)
			->setSubject("Produkce zdravotnického odpadu v ".$this->place)
			->setDescription(
				"Data o produkci zdravotnického odpadu v ".$this->place
			)
			->setKeywords("hcwm tačr tul odpad");
	}

	public function apply(Worksheet $worksheet, array $data): void
	{
		$worksheet->getCell('A1')->setValue($data['geographyLevelName']);
		$worksheet->getCell('A2')->setValue($data['year']);

		//$worksheet->insertNewRowBefore(4,10);
		// make n-1 new rows
		$n = count($data['geographies']);
		if ($n > 1) {
			$worksheet->insertNewRowBefore(4, $n - 1);
		}

		// get styles of each column in row
		$styles = [];
		$startingRow = 3;

		// loop through all geographies all copy style of
		for ($i = 0; $i < count($data['geographies']); $i++) {
			$row = $startingRow + $i;
			$geography = $data['geographies'][$i];
			$worksheet->getCell('A' . $row)->setValue($geography['name']);

			$col = 1;
			foreach ($geography['trashes'] as $key => $trash) {
				//set styles
				$charA = chr(65 + $col);
				$charB = chr(65 + $col + 1);
				$worksheet->getCell($charA . $row)->setValue($trash);
				$worksheet->getCell($charB . $row)->setValue(sprintf('=%s%d/%s%d', $charA, $row, $charA, $n + 3));

				//$worksheet->duplicateStyle($styles[$col], $charA.$row);
				//$worksheet->duplicateStyle($styles[$col+1], $charB.$row);

				$col += 2;
			}

			// total amount
			$char = 'J';
			$arr = [0, 1, 2, 3];
			$arr = array_map(function ($n) use ($row) {
				return chr(65 + 1 + ($n * 2)) . $row;
			}, $arr);

			$worksheet->getCell($char . $row)->setValue(sprintf('=SUM(%s)', implode(',', $arr)));

			//total fraction
			$worksheet->getCell('K' . $row)->setValue(sprintf('=J%d/J%d', $row, $n + 3));
		}
	}

	protected function getRequiredFields(): array
	{
		return [
			'geographyLevelName' => 'string',
			'geographies' => 'array',
			'year' => 'integer',
		];
	}
}
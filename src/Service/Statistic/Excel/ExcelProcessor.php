<?php

declare(strict_types=1);


namespace App\Service\Statistic\Excel;

use App\Service\Statistic\Excel\DataHandler\IExcelDataHandler;
use App\Service\Statistic\Excel\Template\IExcelTemplate;
use App\Service\Util\LoggerTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ExcelProcessor
{

	use LoggerTrait;

	private IExcelTemplate $template;
	private IExcelDataHandler $dataHandler;

	private function isPreparedToProcess(): bool
	{
		return $this->template !== null && $this->dataHandler !== null;
	}

	public function setTemplate(IExcelTemplate $template): void
	{
		$this->template = $template;
	}

	public function setDataHandler(IExcelDataHandler $dataHandler): void
	{
		$this->dataHandler = $dataHandler;
	}

	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function process(): string
	{
		if (!$this->isPreparedToProcess()) {
			$this->getLogger()->error('ExcelProcessor is not prepared to process', [
				'template' => $this->template,
				'dataHandler' => $this->dataHandler,
			]);
			throw new \RuntimeException('ExcelProcessor is not prepared to process');
		}

		$this->getLogger()->info('Processing Excel template', [
			'template' => $this->template->getId(),
		]);

		// is already generated and not expired?
		$generated = $this->getGenerated();
		if ($generated !== null) {
			$this->getLogger()->info('Template already generated', [
				'template' => $this->template->getId(),
				'generated' => $generated,
			]);
			return $generated;
		}

		$file = $this->getTemplateFileName();
		$sheet = IOFactory::load($file);

		$this->template->applyData($sheet, $this->dataHandler);
		$this->template->setSpreadsheetDescription($sheet);

		$writer = IOFactory::createWriter($sheet, 'Xls');
		$generated = $this->getGeneratedFileName();
		$writer->save($generated);

		return $generated;
	}

	private function getGeneratedFileName(): string
	{
		return sprintf(
			'%s/generated/%s.%s',
			IExcelTemplate::TEMPLATE_STORAGE,
			$this->template->getId(),
			$this->template->getFileExtension()
		);
	}

	public function getGenerated(): string|null
	{
		$finder = new Finder();
		$ttl = $this->template->getTTL();
		$it = $finder->files()->in(IExcelTemplate::TEMPLATE_STORAGE.'/generated')->name($this->template->getId().'.*')->date('since '.($ttl > 0 ? '-'.$ttl.' seconds' : 'now'))->getIterator();

		foreach ($it as $file) {
			return $file->getRealPath();
		}

		return null;
	}

	private function getTemplateFileName(): string|null
	{
		$file = sprintf(
			'%s/templates/%s.%s',
			IExcelTemplate::TEMPLATE_STORAGE,
			$this->template->getId(),
			$this->template->getFileExtension()
		);

		// Does file exists?
		if (!file_exists($file)) {
			throw new FileNotFoundException($file);
		}

		return realpath($file);
	}

}
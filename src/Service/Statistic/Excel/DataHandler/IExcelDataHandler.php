<?php
declare(strict_types=1);

namespace App\Service\Statistic\Excel\DataHandler;

interface IExcelDataHandler
{

	public function handleData(): array|false;

}
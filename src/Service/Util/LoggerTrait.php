<?php
declare(strict_types=1);


namespace App\Service\Util;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{

	/**
	 * @var LoggerInterface|null
	 */
	private $logger;
	/**
	 * @required
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function getLogger(): LoggerInterface
	{
		return $this->logger;
	}

}
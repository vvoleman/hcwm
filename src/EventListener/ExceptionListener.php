<?php declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
	public function __invoke(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		if ($exception instanceof HttpException) {
			try {
				$data = $exception->getHeaders();
			} catch (\Throwable) {
				$data = [];
			}

			$responseData = [
				'error' => $exception->getMessage(),
				'code' => $exception->getStatusCode(),
				'data' => $data
				// Add more error details if needed
			];

			$response = new JsonResponse($responseData, $exception->getStatusCode());
			$event->setResponse($response);
		}


	}
}

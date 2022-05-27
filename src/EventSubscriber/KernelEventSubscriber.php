<?php

declare(strict_types=1);


namespace App\EventSubscriber;

use App\Repository\LanguageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventSubscriber implements EventSubscriberInterface
{

	private LanguageRepository $languageRepository;

	public function __construct(LanguageRepository $languageRepository)
	{
		$this->languageRepository = $languageRepository;
	}


	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::REQUEST  => [
				['setLocale',20],
			],
		];
	}

	public function setLocale(RequestEvent $event): void
	{
		$request = $event->getRequest();

		$locale = $request->query->get('_locale');

		if($locale){
			$language = $this->languageRepository->find($locale);
			if($language){
				$request->getSession()->set('_locale', $locale);
				$request->setLocale($language->getCode());
			}
		}else{
			$request->setLocale($request->getSession()->get('_locale') ?? $request->getDefaultLocale());
		}
	}
}
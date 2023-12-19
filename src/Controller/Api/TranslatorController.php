<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\Repository\ItemRepository;
use App\Repository\LanguageRepository;
use App\Service\Translation\Translator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TranslatorController extends BaseApiController
{

	#[Route("/translate",name:"api_translate_get",methods: ["GET"])]
	public function post(Translator $translator, ItemRepository $itemRepository, LanguageRepository $languageRepository){
		$required = ['item'=>true,'language'=>true];

		try {
			$params = $this->getParams($required);
		} catch (MissingParameterException $e){
			return $this->error($e->getMessage(),Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		$item = $itemRepository->find($params["item"]);
		$language = $languageRepository->find($params["language"]);

		$missing = [];
		if(!$item){
			$missing[] = "'item'";
		}
		if(!$language){
			$missing[] = "'language'";
		}

		if(count($missing) > 0){
			return $this->error(sprintf("Couldn't find %s for ID given",implode(",",$missing)),Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		$translation = $translator->translate($item->getLanguage(),$language,$item->getAbstract());

		return $this->send([
			"id"=>$translation->getId(),
			"source"=>$item->getLanguage()->getCode(),
			"target"=>$language->getCode(),
			"translation"=>$translation->getText()
		]);

	}

}

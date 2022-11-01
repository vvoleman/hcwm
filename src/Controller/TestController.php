<?php
declare(strict_types=1);


namespace App\Controller;

use App\Service\Zotero\Updated\ZoteroSyncer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

	// make index
	#[Route('/test/zotero', name: 'test_zotero')]
	public function index(ZoteroSyncer $syncer)
	{
		$syncer->test();
	}

}
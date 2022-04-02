<?php

namespace App\Controller;

use App\Service\Zotero\LoadZoteroCollections;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use ZoteroApi\Source\UsersSource;

class HomeController extends AbstractController
{

    #[Route("/")]
    public function index(LoadZoteroCollections $collections)
    {
        dd($collections->load("frKDBp5JYt9xK2jdV7tKANwn",new UsersSource(9200014)));
    }

}
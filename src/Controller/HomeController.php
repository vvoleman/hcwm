<?php

namespace App\Controller;

use App\Service\Zotero\LoadZoteroCollections;
use App\Service\Zotero\LoadZoteroItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZoteroApi\Source\UsersSource;
use ZoteroApi\ZoteroApi;

class HomeController extends AbstractController
{

    #[Route("/",name:"app_home")]
    public function index(): Response
    {
        return $this->render("home.html.twig");
    }

}
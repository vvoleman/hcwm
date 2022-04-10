<?php

namespace App\Controller;

use App\Exception\CollectionNotFoundException;
use App\Service\Collections\FindCollections;
use App\Service\Zotero\LoadZoteroCollections;
use App\Service\Zotero\LoadZoteroItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Source\UsersSource;
use ZoteroApi\ZoteroApi;

class HomeController extends AbstractController
{

    public function index(FindCollections $findCollections, string $path = ""){
        $parts = explode("/",$path);

        try {
            $result = $findCollections->find(end($parts));
        } catch (CollectionNotFoundException $e){
            $error = CollectionNotFoundException::class;
        }

        return $this->render("list/index.html.twig",[
            "collections"=>$result ?? false,
            "error"=>$error ?? false
        ]);
    }

    public function reload(LoadZoteroCollections $collections, LoadZoteroItems $items)
    {
        $apiKey = "frKDBp5JYt9xK2jdV7tKANwn";
        $source = new UsersSource(9200014);
        $api = new ZoteroApi($apiKey,$source);
//        $api->setEndpoint(new Collections(AbstractEndpoint::ALL));
//        $api->run();
//        dd($api->getBody());
        $collections->load($apiKey,$source);
        $items->loadAllItems($api);
    }

}
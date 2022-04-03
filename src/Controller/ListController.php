<?php

namespace App\Controller;

use App\Exception\CollectionNotFoundException;
use App\Service\Collections\FindCollections;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/list",name:"app_list")]
class ListController extends AbstractController
{
    #[Route("/{path}",name:"",requirements: ["path"=>".+"])]
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

}
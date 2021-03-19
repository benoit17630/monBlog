<?php

namespace App\Controller;

use App\Repository\Admin\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ArticleRepository $repository
     * @return Response
     */
    public function index(ArticleRepository $repository): Response
    {
        // jutilise une requete dql personaliser dans le repository
        // $articles=$repository->findByisPublished(1,["id"=>"desc"],2);
        // $articles= $repository->findAllPublishedLastTwo();
        $articles = $repository->findBy(
            ["isPublished"=> true],
            ["id"=>"DESC"],
            2
        );

        return $this->render('home/index.html.twig', [
            "articles"=>$articles

        ]);
    }


}

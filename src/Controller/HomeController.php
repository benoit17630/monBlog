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
     */
    public function index(ArticleRepository $repository): Response
    {
        // jutilise une requete dql personaliser dans le repository
        $articles= $repository->findAllPublishedLastTwo();
        return $this->render('home/index.html.twig', [
            "articles"=>$articles

        ]);
    }
/*
    /**
     * @Route("/truc", name="truc")
     */
   /* public function bidule(ArticleRepository $repository)
    {
        $articles = $repository->findBy(
            ["isPublished"=> true],
            ["id"=>"DESC"],
            2
            );

        return $this->render('home/index.html.twig', [
            "articles"=>$articles

        ]);
    }*/
}

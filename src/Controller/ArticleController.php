<?php

namespace App\Controller;

use App\Repository\Admin\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     * @param ArticleRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository $repository,
                          PaginatorInterface $paginator,
                          Request $request): Response
    {
        //j utilise knp paginator pour faire une pagination avec 10 articles maximun pour cela je doit metre en paramatre
        //  PaginatorInterface $paginator
        $articles= $paginator->paginate(
            //ici j ai creer une requette pour trouver tous les articles publier du coup j ai pas besoin de faire une
            // verification dans le twig
            $repository->findAllPublished(),
            $request->query->getInt('page',1),8

        );

        return $this->render('article/index.html.twig', [
            "articles"=>$articles


        ]);
    }


    /**
     * @Route("/article/{id}", name="article_show" )
     * @param $id
     * @param ArticleRepository $repository
     * @return Response
     */


    public function show($id, ArticleRepository $repository): Response
    {
        $article= $repository->find($id);
        return $this->render('article/show.html.twig', [
            'article'=>$article

        ]);

    }
}

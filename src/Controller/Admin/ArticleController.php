<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Article;
use App\Form\Admin\ArticleType;
use App\Repository\Admin\ArticleRepository;
use App\Repository\Admin\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="admin_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository,
                          PaginatorInterface $paginator,
                          Request $request,
                          CategoryRepository $categoryRepository): Response
    {
        $articleCategories =$categoryRepository->findAll();
        $articles= $paginator->paginate(

            $articleRepository->findAll(),
            $request->query->getInt('page',1),8

        );


        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
            'articleCategory'=>$articleCategories
        ]);
    }

    /**
     * @Route("/new", name="admin_article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash("success","l article avec le titre ".$article->getName() ." a bien ete enregister");
            return $this->redirectToRoute('admin_article_index');

        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="admin_article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","l article avec le titre " . $article->getName() ." a bien ete modifier");
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash("warning", "l article avec le titre " . $article->getName() . " a bien ete supprimer ");
        }

        return $this->redirectToRoute('admin_article_index');
    }

    /**
     * @Route("/{id}", name="admin_article_category")
     */
    public function articleCategory($id,
                                    ArticleRepository $repository,
                                    PaginatorInterface $paginator,
                                    Request $request,
                                    CategoryRepository $categoryRepository)

    {
        $articleCategories =$categoryRepository->findAll();

        $articles= $paginator->paginate(
            $articles = $repository->findby(["category"=>$id]),
            $request->query->getInt('page',1),8
        );

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
            'articleCategory'=>$articleCategories
        ]);
    }
}

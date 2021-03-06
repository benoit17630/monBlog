<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Article;
use App\FileManager\FileManager;
use App\Form\Admin\ArticleType;
use App\Repository\Admin\ArticleRepository;
use App\Repository\Admin\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $request->query->getInt('page',1),4

        );


        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
            'articleCategory'=>$articleCategories
        ]);
    }

    /**
     * @Route("/new", name="admin_article_new", methods={"GET","POST"})
     */
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        FileManager $fileManager): Response
    {
        // Je créé une nouvelle instance de l'entité Article
        // pour créer un nouveau enregistrement en bdd
        $article = new Article();

        // je veux afficher un formulaire pour créer des articles
        // donc je viens récupérer le gabarit de formulaire ArticleType créé en ligne de commandes
        // en utilisant la méthode createForm de l'AbstractController (et je lui passe en parametre
        // le gabarit de formulaire à créer)
        $form = $this->createForm(ArticleType::class, $article);

        // Je viens lier le formulaire créé
        // à la requête POST
        // de cette manière, je pourrai utiliser la variable $form
        // pour vérifier si les données POST ont été envoyées ou pas
        $form->handleRequest($request);

        // si le formulaire a été envoyé et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();



             $fileManager->saveFile($article, $form);

          //  $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash("success","l article avec le titre ".$article->getName() ." a bien ete enregister");
            return $this->redirectToRoute('admin_article_index');

        }

        // je récupère (et compile) le fichier twig et je lui envoie le formulaire, transformé
        // en vue (donc exploitable par twig)
        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_article_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function edit(Request $request,
                         Article $article,
                         EntityManagerInterface $entityManager,
                         FileManager $fileManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $fileManager->saveFile($article, $form);



            // et j'enregistre l'article en bdd
            $entityManager->persist($article);
            $entityManager->flush();
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
     * @Route("/{name}", name="admin_article_category")
     */
    public function articleCategory($name,
                                    PaginatorInterface $paginator,
                                    Request $request,
                                    CategoryRepository $categoryRepository): Response

    {
        $articleCategories =$categoryRepository->findAll();

        $categories= $paginator->paginate(
            $categoryRepository->findBy(["name"=>$name]),
            $request->query->getInt('page',1),4
        );

        return $this->render('admin/article/categorie.html.twig', [
            'categories' => $categories,
            'articleCategory'=>$articleCategories
        ]);
    }
}

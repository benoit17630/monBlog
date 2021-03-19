<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Color;
use App\Form\Admin\ColorType;
use App\Repository\Admin\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/color")
 */
class ColorController extends AbstractController
{
    /**
     * @Route("/", name="admin_color_index", methods={"GET"})
     */
    public function index(ColorRepository $colorRepository): Response
    {
        return $this->render('admin/color/index.html.twig', [
            'colors' => $colorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_color_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $color = new Color();
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($color);
            $entityManager->flush();

            return $this->redirectToRoute('admin_color_index');
        }

        return $this->render('admin/color/new.html.twig', [
            'color' => $color,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="admin_color_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Color $color): Response
    {
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_color_index');
        }

        return $this->render('admin/color/edit.html.twig', [
            'color' => $color,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_color_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Color $color): Response
    {
        if ($this->isCsrfTokenValid('delete'.$color->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($color);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_color_index');
    }
}

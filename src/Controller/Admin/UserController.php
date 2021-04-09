<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users= $userRepository->findAll();
        return $this->render('admin/user/index.html.twig', [
            'users'=>$users
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit" )
     */
    public function edit($id,
                         User $user,
                         Request $request,
                         EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render("admin/user/edit.html.twig",[
            'user'=> $user,
            'form'=>$form->createView()
        ]);
    }
}

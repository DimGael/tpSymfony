<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="admin_user_list", methods={"GET"})
     */
    public function index(UserRepository $repository)
    {
        return $this->render("admin/equipe/admin_user_list.html.twig", [
            "users" => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="admin_user_show", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function show(int $id, UserRepository $repository)
    {
        return $this->render("admin/equipe/admin_user_show.html.twig", [
            "user" => $repository->find($id),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_user_delete", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function delete(int $id, UserRepository $repository)
    {
        $user = $repository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'danger',
            "L'utilisateur " . $user->getUsername() . " a bien été supprimé"
        );

        return $this->redirectToRoute("admin_user_list");
    }


    /**
     * @Route("/new", name="admin_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $repository, ?User $user)
    {
        if($user)
            $title = "Modification de ".$user->getUsername();
        else{
            $title = "Nouvel utilisateur";
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()) {
                $user->setCreatedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    "L'utilisateur " . $user->getUsername() . " a été ajouté !"
                );

                return $this->redirectToRoute("admin_user_list");
            }
            else{
                $this->addFlash(
                    'danger',
                    "Erreur : L'utilisateur n'a pas pu être ajouté."
                );
            }
        }

        return $this->render("admin/equipe/admin_user_new.html.twig", [
            "form" => $form->createView(),
            "title" => $title
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET", "POST"}, requirements={"id" = "\d+"})
     */
    public function edit(Request $request, UserRepository $repository, int $id)
    {
        $user = $repository->find($id);
        $user->setUpdatedAt(new \DateTime());

        $this->addFlash('info',
            "L'utilisateur " .$user->getUsername() . " a bien été modifié");

        return $this->new($request, $repository, $user);
    }
}

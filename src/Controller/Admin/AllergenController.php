<?php

namespace App\Controller\Admin;

use App\Entity\Allergen;
use App\Form\AllergenType;
use App\Repository\AllergenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/allergen")
 */
class AllergenController extends AbstractController
{
    /**
     * @Route("/", name="allergen_index", methods={"GET"})
     */
    public function index(AllergenRepository $allergenRepository): Response
    {
        return $this->render('admin/allergen/index.html.twig', [
            'allergens' => $allergenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="allergen_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergen = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();


            $this->addFlash('success',
                "L'allergène ".$allergen->getName()." a bien été ajouté.");


            $entityManager->persist($allergen);
            $entityManager->flush();

            return $this->redirectToRoute('allergen_index');
        }

        return $this->render('admin/allergen/new.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="allergen_show", methods={"GET"})
     */
    public function show(Allergen $allergen): Response
    {
        return $this->render('admin/allergen/show.html.twig', [
            'allergen' => $allergen,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="allergen_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Allergen $allergen): Response
    {
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('info',
                "L'allergène ".$allergen->getName()." a bien été modifié.");

            return $this->redirectToRoute('allergen_index', [
                'id' => $allergen->getId(),
            ]);
        }

        return $this->render('admin/allergen/edit.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="allergen_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Allergen $allergen): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergen->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($allergen);
            $entityManager->flush();

            $this->addFlash('danger',
                "L'allergène ".$allergen->getName()." a bien été supprimé.");
        }

        return $this->redirectToRoute('allergen_index');
    }
}

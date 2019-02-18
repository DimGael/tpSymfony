<?php

namespace App\Controller\Admin;

use App\Entity\Allergen;
use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\AllergenRepository;
use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DishController
 * @package App\Controller
 * @Route("/admin/dish")
 */
class DishController extends AbstractController
{

    /**
     * @Route("/", name="admin_dish_list", methods={"GET"})
     */
    public function listDishes(DishRepository $dishRepository)
    {
        $dishes = $dishRepository->findAll();

        return $this->render("admin/dish/dishlist.html.twig", array(
            "dishes" => $dishes
        ));
    }

    /**
     * @Route("/insertJson", name="admin_dish_insert_json", methods={"GET"})
     */
    public function insererPlatsEtAllergenes(Request $request, DishRepository $dishRepository, AllergenRepository $allergenRepository, CategoryRepository $categoryRepository){
        $json = file_get_contents(__DIR__ . "/../insert.json");
        $json = json_decode($json, true);


        foreach ($json["dishes"] as $dish_values){
            if($existing_dish = $dishRepository->find($dish_values["id"]))
                $dish = $existing_dish;
            else
                $dish = new Dish();

            $dish->setCalories($dish_values["calories"]);
            $dish->setCategory($categoryRepository->find($dish_values["category_id"]));
            $dish->setDescription($dish_values["description"]);
            $dish->setName($dish_values["name"]);
            $dish->setPrice($dish_values["price"]);
            $dish->setImage($dish_values["image"]);
            $dish->setSticky(true);

            $this->getDoctrine()->getManager()->persist($dish);
            $this->getDoctrine()->getManager()->flush();
        }

        foreach ($json["allergens"] as $allergen_values){
            if($existing_allergen = $allergenRepository->find($allergen_values["id"]))
                $allergen = $existing_allergen;
            else
                $allergen = new Allergen();

            $allergen->setName($allergen_values["name"]);

            foreach ($allergen_values["id_dishes"] as $id_dish)
                $allergen->addDish($dishRepository->find($id_dish));

            $this->getDoctrine()->getManager()->persist($allergen);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect("/carte");
    }

    /**
     * @Route("/{id}/show", name="admin_dish_show", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function showDish(DishRepository $dishRepository, int $id)
    {
        $dish = $dishRepository->find($id);

        return $this->render("admin/dish/dish_detail.html.twig", array(
            "dish" => $dish
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_dish_edit", methods={"GET", "POST"}, requirements={"id" = "\d+"})
     */
    public function editDish(Request $request, DishRepository $dishRepository, DishType $dishType, int $id)
    {
        $dish = $dishRepository->find($id);

        return $this->insertDish($request, $dishType, $dish);
    }

    /**
     * @Route("/new", name="admin_dish_new", methods={"GET", "POST"})
     */
    public function insertDish(Request $request, DishType $dishType, Dish $dish = null)
    {
         // Si dish est null
        if(!$dish){
            $dish = new Dish();
            $title = "Nouveau Plat";
        }
        else{
            $title = "Modification de ".$dish->getName();
        }

        $formBuilder = $this->createFormBuilder($dish);

        $dishType->buildForm($formBuilder, array());

        $form = $formBuilder->getForm()
            ->add("Save", SubmitType::class);

        //Prise en charge du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dish = $form->getData();
            $allergens = $dish->getAllergens();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($dish);
            $entityManager->flush();

            foreach ($allergens as $allergen) {
                $entityManager->persist($allergen);
                $entityManager->flush();
            }

            return $this->redirectToRoute("admin_dish_list");
        }

        return $this->render("admin/dish/dish_form.html.twig", array(
            "form" => $form->createView(),
            "title" => $title
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_dish_delete", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function deleteDish(DishRepository $dishRepository, int $id)
    {
        $dish = $dishRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($dish);
        $entityManager->flush();

        return $this->redirectToRoute("admin_dish_list");
    }
}

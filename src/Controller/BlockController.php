<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{

    public function dayDishes(DishRepository $dishRepository, CategoryRepository $categoryRepository, int $max = 3)
    {
        $category = $categoryRepository->findBy(
            [
                "name" => "Plat"
            ]
        );

        $dishes = $dishRepository->findBy(
            [
                'sticky' => true,
                'Category' => $category
            ],
            [
                "id" => 'DESC'
            ],
            3
        );

        return $this->render("Partial/day_dishes.html.twig",
            array('dishes' => $dishes)
        );
    }
}

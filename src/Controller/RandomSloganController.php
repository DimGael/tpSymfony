<?php

namespace App\Controller;

use App\Service\RandomSlogan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RandomSloganController extends AbstractController
{
    /** @var RandomSlogan */
    private $randomSlogan;

    /**
     * RandomSloganController constructor.
     * @param RandomSlogan $randomSlogan
     */
    public function __construct(RandomSlogan $randomSlogan)
    {
        $this->randomSlogan = $randomSlogan;
    }


    /**
     * @Route("/slogan", name="random_slogan")
     */
    public function index()
    {
        /** @var string $slogan */
        $slogan = $this->randomSlogan->getSlogan("Kiwi");

        $this->randomSlogan->getLogger()->info($slogan);

        return $this->render('random_slogan/slogan.html.twig', [
            'slogan' => $slogan,
        ]);
    }
}

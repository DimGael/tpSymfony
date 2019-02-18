<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 16/11/18
 * Time: 13:55
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Services\RhService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends Controller
{

    /**
     * @Route("/", name="front_home")
     */
    public function index(){
        return $this->render('front/index.html.twig', [
        ]);
    }

    /**
     * @Route("/equipe/new", name="admin_team_insert", methods={"GET"})
     * http://127.0.0.1:8000/admin/equipe/inserer?username=pat&email=patoche@gmail.com&firstname=Patrick&lastname=londuba&jobtitle=Sorcier
     */
    public function inserer(Request $request)
    {
        $user = new User();

        dump($request->query->all());

        $user->setUsername($request->query->get("username"));
        $user->setEmail($request->query->get("email"));
        $user->setFirstname($request->query->get("firstname"));
        $user->setLastname($request->query->get("lastname"));
        $user->setJobtitle($request->query->get("jobtitle"));

        $user->setEnabled(true);

        $user->setCreatedAt(new \DateTime("now"));

        //Sauvegarde l'objet
        if($request->isMethod("GET")){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute("front_team");
    }

    /**
     * @Route("/equipe", name="front_team", methods={"GET"})
     */
    public function team(UserRepository $userRepository){

        /** @var RhService $rhService */
        $rhService = $this->get('restau.service.sh');

        return $this->render('front/front_team.html.twig', [
            'list_team' => $rhService->getDayTeam(new \DateTime()),
        ]);
    }

    /**
     * @Route("/carte", name="front_dishes", methods={"GET"})
     */
    public function dishes(CategoryRepository $categoryRepository){
        $listCategory = $categoryRepository->findAll();

        return $this->render("front/front_categories.html.twig",[
            'categories' => $listCategory
        ]);
    }

    /**
     * @Route("/carte/{idCat}", name="front_dishes_category", methods={"GET"}, requirements={"idCat"="\d+"})
     */
    public function categories(Request $request, CategoryRepository $categoryRepository, int $idCat)
    {
        if (!$category = $categoryRepository->find($idCat)) {
            throw new NotFoundHttpException("La catégorie " . $idCat . " n'existe pas");
        }

        $listDishes = $category->getDishes();

        return $this->render("front/front_category.html.twig", [
            'dishes' => $listDishes,
            'category' => $category
        ]);
    }

    /**
     * @Route("/mentions-legales", name="front_legals", methods={"GET"})
     */
    public function mentionsLegales()
    {
        return $this->render("Layout/front_legals.html.twig");
    }
}
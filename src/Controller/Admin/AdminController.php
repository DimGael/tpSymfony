<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 06/02/19
 * Time: 16:17
 */

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index():Response
    {
        return $this->render('admin/base.html.twig');
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $entityManager;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(){
        $users = $this->getDoctrine()->getRepository(User::class)-> findAll();
        return $this->render("admin/index.html.twig", [
            "users" => $users
        ]);
    }
}

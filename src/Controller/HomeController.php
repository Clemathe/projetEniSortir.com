<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="home")
     */
    public function accueil(Request $request, SortieRepository $sortieRepo): Response
    {
        if (is_null($this->security->getUser())){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('home/accueil.html.twig', []);
    }
}

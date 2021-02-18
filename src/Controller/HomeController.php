<?php

namespace App\Controller;

use App\data\FindSortie;
use App\Form\FindForm;
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
        $data = new FindSortie();
        $form =$this->createForm(FindForm::class,$data);
        $sorties = $sortieRepo->findSearch();

        return $this->render('home/accueil.html.twig', ['sortie' => $sorties,
            'form'=> $form->createView()]);
    }
}

<?php

namespace App\Controller;

use App\data\FindSortie;
use App\Form\FindForm;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function accueil(Request $request, SortieRepository $sortieRepo, EntityManagerInterface $em): Response
    {
        if (is_null($this->security->getUser())){
            return $this->redirectToRoute('app_login');

        }
        // Execution de la procedure stockée de mise à jour des états
        $stmt = $em->getConnection()->prepare("CALL miseAJourEtat()");
        $stmt->fetchAll();

        $data = new FindSortie();
        $form =$this->createForm(FindForm::class,$data);
        $form->handleRequest($request);

        $sorties = $sortieRepo->findSearch($data);
        if ($sorties == null){
            $this->addFlash('danger', 'Auncun résultat');
}
        return $this->render('home/accueil.html.twig', ['sorties' => $sorties,
            'form'=> $form->createView()]);
    }
}

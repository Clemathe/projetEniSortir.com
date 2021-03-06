<?php

namespace App\Controller;

use App\data\FindSortie;
use App\Form\FindForm;
use App\Models\EtatUpdater;
use App\Repository\SortieRepository;
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
    public function accueil(Request $request, SortieRepository $sortieRepo,
                           EntityManagerInterface $em, EtatUpdater $updateEtat): Response
    {
        if (is_null($this->security->getUser())){
            return $this->redirectToRoute('app_login');

        }
        // Appel de la méthode pour le lancement de la procedure stockée de mise à jour des états
        //$updateEtat->miseAJourEtat($em);

        $data = new FindSortie();
        $data->page=$request->get('page',1);
        $form =$this->createForm(FindForm::class,$data);
        $form->handleRequest($request);
        $inscrit= $this->getUser()->getSorties();


        $sorties = $sortieRepo->findSearch($data);
        if ($sorties === null){
            $this->addFlash('success', 'Aucun résultat');
        }

        return $this->render('home/accueil.html.twig', ['sorties' => $sorties,
            'form'=> $form->createView()]);
    }
}

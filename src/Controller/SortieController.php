<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 * @package App\Controller
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/new", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request ): Response
    {
        $sortie = new sortie();

        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

            $sortie->setOrganiser(null); //TODO: recuperer l'utilisateur en session

            // instancie Etat et récupère l'état via les boutons publier ou enregistrer
            $etat = new etat();
            $etat->setId($request->request->get('etat'));
            $sortie->setEtat($etat);
            dump($request->request->get("etat"));


//            $em->persist($sortie);
//            $em->flush();

            if ($etat->getId() == 2){
                $this->addFlash('success', 'La sortie a été publiée');

            }else {
                $this->addFlash('success', 'La sortie a été sauvegardée');
            }
            return $this->redirectToRoute('home',
                []);

        }

        return $this->render('sortie/nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView()]);
    }
}

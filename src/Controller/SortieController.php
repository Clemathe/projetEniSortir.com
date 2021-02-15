<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
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

            //recupere l'user en session et instancie un organisteur
            $organiser = new user();
            //TODO: recuperer l'utilisateur en session et setter $organiser
            $sortie->setOrganiser($organiser);

            // instancie Etat et récupère l'état via les boutons publier ou enregistrer
            $etat = new etat();
            $etat->setId($request->request->get('etat'));
            $sortie->setEtat($etat);
            dump($request->request->get("etat"));

//            $em->persist($sortie);
//            $em->flush();

            //Gestion de l'affichage d'un message de succès ou d'echec
            if ($etat->getId() == 2){
                $this->addFlash('success', 'La sortie a été publiée');
            }else if ($etat->getId() == 1) {
                $this->addFlash('success', 'La sortie a été sauvegardée');
            }else{
                $this->addFlash('error', 'Un problème est survenu');
            }
            return $this->redirectToRoute('home', []);

        }

        return $this->render('sortie/nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView()]);
    }
}

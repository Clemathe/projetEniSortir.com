<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class SortieController
 * @package App\Controller
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/new", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request, Sortie $sortie = null, UserRepository $userRepo, EtatRepository $etatRepo): Response
    {
        $sortie = new sortie();

        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

//        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
//
//            //recuperer l'user en session et instancie un organisteur
//
//            $id = $this->security->getUser()->getId();
//            $organiser = $userRepo->find($id);
//
//            $organiser->addEventCreated($sortie);
//            $sortie->setOrganiser($organiser);
//
//            // instancie Etat et récupère l'état via les boutons publier ou enregistrer
//
//            $id = $request->request->get('etat');
//            $etat = $etatRepo->find($id);
//            $sortie->setEtat($etat);
////            dd($etat);
//
////            $em->persist($sortie);
////            $em->flush();

//            //Gestion de l'affichage d'un message de succès ou d'echec
//            if ($etat->getId() == 2){
//                $this->addFlash('success', 'La sortie a été publiée');
//            }else if ($etat->getId() == 1) {
//                $this->addFlash('success', 'La sortie a été sauvegardée');
//            }else{
//                $this->addFlash('error', 'Un problème est survenu');
//            }
//            return $this->redirectToRoute('home', []);

//        }

        return $this->render('sortie/nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView()]);
    }

    /**
     * @Route("/inscription", name="sortie_inscription")
     */
    public function inscription(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request)
    {
        $idSortie = $request->request->get('id');
        $sortie = $sortieRepo->find($idSortie);

        /* @var $user User */
        $user = $this->security->getUser();
        $user->addSortie($sortie);
        $em->persist($user);
        $em->flush();

        if ($user->getSorties()[($user->getSorties()->count()) - 1]->getId() == $sortie->getId()){
                $this->addFlash('success', 'Vous êtes inscrits');
        }else{
            $this->addFlash('error', 'Un problème est survenu');
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desinscription", name="sortie_desinscription")
     */
    public function desinscription(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request)
    {
        /* @var $user User*/
        $user = $this->security->getUser();

        $idSortie = $request->request->get('id');
        $sortie = $sortieRepo->find($idSortie);
        $nbDeSortie = $user->getSorties()->count();

        $user->removeSortie($sortie);
        $em->persist($user);
        $em->flush();


        $this->addFlash('error', 'Vous êtes desinscrits');

        return $this->redirectToRoute('home');
    }


}

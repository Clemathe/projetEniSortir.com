<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
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
     * @Route("/inscription/{id}", name="sortie_inscription", requirements={"id" : "\d+"})
     */
    public function inscription(EntityManagerInterface $em, SortieRepository $sortieRepo, $id)
    {
        $sortie = $sortieRepo->find($id);
        dump('$sortie');

        // Si les inscriptions sont ouvertes
        if ($sortie->getEtat()->getLibelle() == 'Ouverte') {
            dump('a');

            // Si la date limite pour les inscritptions n'est pas dépassée
            if ($sortie->getDeadline() > new \DateTime()) {
                dump('a');

                // Si le nombre maximum de participants n'est pas depassé
                if ($sortie->getMaxNbOfRegistration() >= $sortie->getUsers()->count() ) {

                    /* @var $user User */
                    $user = $this->security->getUser();

                    // si l'user n'est pas déjà inscrit
                    if (!$sortie->getUsers()->contains($user)){
                        dump('a');
                        $user->addSortie($sortie);
                        $sortie->addUser($user); //TODO Faut il mieux le coder dans la méthode addSortie de User ou inversement ?

                        $em->persist($user);
                        $em->flush();

                        $this->addFlash('success', 'Vous êtes inscrits');
                    }
                    else{
                        $this->addFlash('error', 'Vous êtes déjà inscrit');
                    }
                }else{
                    $this->addFlash('error', 'Le nombre maximun de participant est déjà atteint');
                }
            } else {
                $this->addFlash('error', 'La date d\'inscription est dépassée');
            }
        }else{
            $this->addFlash('error', 'Les inscriptions ne sont pas ouvertes');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desinscription/{id}", name="sortie_desinscription", requirements={"id" : "\d+"})
     */
    public function desinscription(EntityManagerInterface $em, SortieRepository $sortieRepo, $id)
    {

        $sortie = $sortieRepo->find($id);

        // Si la date actuelle est plus petite que la date de la sortie
        if( new \DateTime < $sortie->getStartedDateTime())
        {
            /* @var $user User */
            $user = $this->security->getUser();

            $user->removeSortie($sortie);
            $sortie->removeUser($user); //TODO Faut il mieux le coder dans la méthode removeSortie de User ou inversement ?

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Vous êtes desinscrits de la sortie');
        }else{
            $this->addFlash('error', 'La sortie a déjà commencée, impossible de se désinscrire');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annulation", name="sortie_annulation")
     */
    public function annulation(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request)
    {
        /* @var $user User */
        $user = $this->security->getUser();

        $idSortie = $request->request->get('idSortie');
        $sortie = $sortieRepo->find($idSortie);

        // Si l'id utilisateur est égal à l'id organisateur
        if($user->getId() == $sortie->getOrganiser()->getId())
        {
            $particpants = $sortie->getUsers();
        }

    }
    /**
     * @Route("/modification", name="sortie_modification")
     */
    public function modification(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request)
    {
        //TODO: gerer la modif si etat=1 et faire apparaitre le bouton ou non dans la vue
    }


}

<?php

namespace App\Controller;



use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieFormType;
use App\Models\LogicalModels;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use DateTime;
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
    public function add(EntityManagerInterface $em, Request $request,
                        UserRepository $userRepo, EtatRepository $etatRepo): Response
    {
        //Création et set de l'entité
        $sortie = new sortie();
        $sortie->setDeadline(new \DateTime());
        $sortie->setStartedDateTime(new \DateTime());
        $sortie->setCampus($this->security->getUser()->getCampus());
        $sortie->setDuration(1);
        $sortie->setMaxNbOfRegistration(1);


        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            // Recuperère l'user en session et instancie un organisteur
            $id = $this->security->getUser()->getId();
            $organiser = $userRepo->find($id);

            $organiser->addEventCreated($sortie);
            $sortie->setOrganiser($organiser);

            // Instancie Etat et récupère l'état via les boutons publier (2) ou enregistrer (1)
            $id = $request->request->get('etat');
            $etat = $etatRepo->find($id);
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();

            //Gestion de l'affichage d'un message de succès ou d'echec en fonction de l'état de la sortie
            if ($etat->getId() == 2) {
                $this->addFlash('success', 'La sortie a été publiée');
            } else if ($etat->getId() == 1) {
                $this->addFlash('success', 'La sortie a été sauvegardée');
            } else {
                $this->addFlash('error', 'Un problème est survenu');
            }
            return $this->redirectToRoute('home', []);

        }


        return $this->render('nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView(),]);
    }

    /**
     * @Route("/inscription/{id}", name="sortie_inscription", requirements={"id" : "\d+"})
     *
     * Permet l'incription d'un utilisateur à une sortie
     */
    public function inscription(EntityManagerInterface $em, SortieRepository $sortieRepo,
                                $id, LogicalModels $modeleLogique): Response
    {
        $sortie = $sortieRepo->find($id);

        /* @var $user User */
        $user = $this->security->getUser();

        // Test différentes contraintes pour accepter ou refuser une inscription à une sortie et retourne un message
        $message = $modeleLogique->logicalConstraintsToSaveANewRegistration($user, $sortie, $em);

        $this->addFlash($message[0], $message[1]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desinscription/{id}", name="sortie_desinscription", requirements={"id" : "\d+"})
     * @Route("/desinscription/{id}/{profil}", name="sortie_desinscription_profil", requirements={"id" : "\d+"})
     *
     * Permet à l'utilisateur de se désincrire d'une sortie
     */
    public function desinscription(EntityManagerInterface $em, SortieRepository $sortieRepo, $id, $profil = null): Response
    {

        $sortie = $sortieRepo->find($id);

        // Si la date actuelle est plus petite que la date de la sortie
        if (new DateTime < $sortie->getStartedDateTime()) {
            /* @var $user User */
            $user = $this->security->getUser();

            $user->removeSortie($sortie);
            $sortie->removeUser($user);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Vous êtes desinscrits de la sortie');

            if ($profil)
                return $this->redirectToRoute('user_profil');
        } else {
            $this->addFlash('error', 'La sortie est commencée ou a déjà eu lieu, impossible de se désinscrire');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annulation/{id}", name="sortie_annulation", requirements={"id" : "\d+"})
     *
     * Permet l'annulation d'une sortie
     */
    public function annulation(EntityManagerInterface $em, SortieRepository $sortieRepo,
                               EtatRepository $etatRepo, LogicalModels $modeleLogique, $id): Response
    {
        /* @var $user User */
        $user = $this->security->getUser();

        $sortie = $sortieRepo->find($id);

        $etat = $etatRepo->find($id = 6);

        $message = $modeleLogique->logicalConstraintsToCanceledASortie($user, $sortie, $etat, $em);

        $this->addFlash($message[0], $message[1]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/modification/{id}", name="sortie_modification", requirements={"id" : "\d+"})
     */
    public
    function modification(Request $request, Sortie $sortie): Response
    {
        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);


        return $this->render('nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView(), 'sortie' => $sortie]);


    }


}

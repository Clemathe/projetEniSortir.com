<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\LieuType;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use App\Entity\Lieu;
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
        $sortie->setDeadline(new \DateTime());
        $sortie->setStartedDateTime(new \DateTime());
        $sortie->setCampus($this->security->getUser()->getCampus());

        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
//            dd( $request->request->get('etat'));
//            dd(($sortie));
//            dd($request->request->get('sortie_form[ville]'));
            //recuperer l'user.csv en session et instancie un organisteur

            $id = $this->security->getUser()->getId();
            $organiser = $userRepo->find($id);

            $organiser->addEventCreated($sortie);
            $sortie->setOrganiser($organiser);

            // instancie Etat et récupère l'état via les boutons publier ou enregistrer

            $id = $request->request->get('etat');
            $etat = $etatRepo->find($id);
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();

            //Gestion de l'affichage d'un message de succès ou d'echec
            if ($etat->getId() == 2) {
                $this->addFlash('success', 'La sortie a été publiée');
            } else if ($etat->getId() == 1) {
                $this->addFlash('success', 'La sortie a été sauvegardée');
            } else {
                $this->addFlash('error', 'Un problème est survenu');
            }
            return $this->redirectToRoute('home', []);

        }


        return $this->render('sortie/nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView(), 'lieuForm' => $lieuForm->createView() ]);
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
                if ($sortie->getMaxNbOfRegistration() >= $sortie->getUsers()->count()) {

                    /* @var $user User */
                    $user = $this->security->getUser();

                    // si l'user.csv n'est pas déjà inscrit
                    if (!$sortie->getUsers()->contains($user)) {
                        dump('a');
                        $user->addSortie($sortie);
                        $sortie->addUser($user); //TODO Faut il mieux le coder dans la méthode addSortie de User ou inversement ?

                        $em->persist($user);
                        $em->flush();

                        $this->addFlash('success', 'Vous êtes inscrits');
                    } else {
                        $this->addFlash('danger', 'Vous êtes déjà inscrit');
                    }
                } else {
                    $this->addFlash('danger', 'Le nombre maximun de participant est déjà atteint');
                }
            } else {
                $this->addFlash('danger', 'La date d\'inscription est dépassée');
            }
        } else {
            $this->addFlash('danger', 'Les inscriptions ne sont pas ouvertes');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desinscription/{id}", name="sortie_desinscription", requirements={"id" : "\d+"})
     * @Route("/desinscription/{id}/{profil}", name="sortie_desinscription_profil")
     */
    public function desinscription(EntityManagerInterface $em, SortieRepository $sortieRepo, $id, $profil = null)
    {

        $sortie = $sortieRepo->find($id);

        // Si la date actuelle est plus petite que la date de la sortie
        if (new \DateTime < $sortie->getStartedDateTime()) {
            /* @var $user User */
            $user = $this->security->getUser();

            $user->removeSortie($sortie);
            $sortie->removeUser($user); //TODO Faut il mieux le coder dans la méthode removeSortie de User ou inversement ?

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
     * @Route("/annulation", name="sortie_annulation")
     */
    public function annulation(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request)
    {
        /* @var $user User */
        $user = $this->security->getUser();

        $etat = new Etat();


        $idSortie = $request->request->get('idSortie');
        $sortie = $sortieRepo->find($idSortie);

        // Si l'id utilisateur est égal à l'id organisateur

        if ($user->getId() == $sortie->getOrganiser()->getId()) {


            //Si la date de la sortie est déjà dépassé
            if (new \DateTime < $sortie->getStartedDateTime()) {
                $participants = $sortie->getUsers();
                // Supprime la sortie dans la liste des sorties des users
                foreach ($participants as $participant) {
                    $participant->removeSortie(); // TODO Plutot que de supprmer les particpants, gerer l'affichage des sorties dans une categorie de sortie annulée
                }
                //Supprime la sortie de la liste des sorties crées de l'utilisateur
                // $user.csv->removeEventCreated($sortie);
                $etat->setId(6);
                $sortie->setEtat($etat);
                $em->persist($user);
                $em->flush();

            } else {
                $this->addFlash('error', 'La sortie est terminée, impossible de l\'annuler');
            }
        } else {
            $this->addFlash('error', 'Vous n\'êtes pas l\'organisateur, impossible de supprimer la sortie');
        }


    }

    /**
     * @Route("/modification/{id}", name="sortie_modification", requirements={"id" : "\d+"})
     */
    public function modification(EntityManagerInterface $em, SortieRepository $sortieRepo, Request $request, Sortie $sortie)
    {
        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);


        return $this->render('sortie/nouvelleSortie.html.twig', ['sortieForm' => $sortieForm->createView(), 'sortie' => $sortie]);

        //TODO: gerer la modif si etat=1 et faire apparaitre le bouton ou non dans la vue
    }


}

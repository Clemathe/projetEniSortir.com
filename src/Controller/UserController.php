<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_inscription")
     */
    public function AddNewUser(Request $request,
                               EntityManagerInterface $em,
                               UserPasswordEncoderInterface $encoder,
                               SluggerInterface $slugger, FileUploader $fileUploader): Response
    {   //creation d'un utilisateur
        $user = new User();
        //actif par default
        $user->setActif(1);
        // user par default
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //Ajout de la photo
            $this->setProfilPhoto($form, $user, $fileUploader, $slugger);

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            try {
                $em->flush();
            } catch (\Exception $error) {
                $this->addFlash("danger", "creation de votre compte impossible");
                return $this->render("user/inscription.html.twig", [
                    "form" => $form->createView(),
                    "user" => $user,
                ]);
            }
            $this->addFlash('success', 'Bienvenue sur sortir.eni, votre inscritpion a bien été prise en compte!');
            return $this->redirectToRoute('home');

        }
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
     * @Route("/profil/{id}", name="other_user_profil", requirements={"id" : "\d+"})
     */
    public function profilView(UserRepository $userRepo, $id = null, Security $security, SortieRepository $sortieRepo)
    {

        // Pour les profils utilisateurs en bdd sauf celui en session
        if (isset($id)) {
            $user = $userRepo->find($id);
            $sorties = $sortieRepo->getSortiesUser($id);

            //Pour l'utilisateur en session
        } else {
            $user = $security->getUser();
            $sorties = $sortieRepo->getSortiesUser();
        }

        return $this->render('user/profil.html.twig', [
            'user' => $user, 'sorties' => $sorties]);
    }

    /**
     * @Route ("/edit/{id}", name="user_edit", methods={"GET","POST"})
     */
    public function editProfil(Request $request,
                               User $user, UserPasswordEncoderInterface $encoder,
                               SluggerInterface $slugger, FileUploader $fileUploader)
    {


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->setProfilPhoto($form, $user, $fileUploader, $slugger);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La modification a été enregistrée');

            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    public function setProfilPhoto($form, $user, $fileUploader, $slugger)
    {

        $photoFile = $form->get('urlPhoto')->getData();
        if ($photoFile) {
            try {
                $photoFileName = $fileUploader->upload($photoFile, $slugger);
                $user->setUrlPhoto($photoFileName);

            } catch (FileException $e) {

                $this->addFlash('danger', 'Une erreur s\'est produite');

                return $this->redirectToRoute('user_profil');
            }
        }
    }

}

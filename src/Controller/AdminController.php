<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Form\FileFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileSerializer;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, FileUploader $fileUploader, SluggerInterface $slugger, FileSerializer $fileSerializer)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $file = new File();
        $fileForm = $this->createForm(FileFormType::class, $file );
        $fileForm->handleRequest($request);

        if ($fileForm->isSubmitted()) {

            $this->getUploadedFiles($fileForm, $fileUploader, $slugger, $fileSerializer);

        }

        return $this->render("admin/index.html.twig", [
            "users" => $users,
            'fileForm' => $fileForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/creatUser", name="admin_createuser")
     */
    public function adminCreateUser(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $em->persist($user);
            try {
                $em->flush();
            } catch (\Exception $error) {
                $this->addFlash("danger", "creation impossible");
                return $this->render("Admin/adminInscription.html.twig", [
                    "form" => $form->createView(),
                    "user" => $user,
                ]);
            }
            $this->addFlash("success", "utlisateur crée    " . $user->getUsername());
            return $this->redirectToRoute('admin');
        }
        return $this->render("Admin/adminInscription.html.twig", [
            "form" => $form->createView(),
            "user" => $user,
        ]);

    }

    /**
     * @Route ("/admin/{id}/modifyUser", name="admin_profiluser", methods={"GET","POST"})
     * @param $id
     * @throws Exception
     */
    public function adminEditUser(User $user,
                                  Request $request,
                                  EntityManagerInterface $em,
                                  UserPasswordEncoderInterface $encoder)
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $userPassword = $user->getPassword();
        if ($form->isSubmitted() && $form->isValid()) {
            if (trim($user->getPassword()) == !0) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
            } else {
                $user->setPassword($userPassword);
            }
            $em->flush();
            $this->addFlash('success', 'La modification a été enregistrée');
            return $this->redirectToRoute('admin');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/admin/ActivateUser/{id}", name="admin_activateuser", methods={"GET","POST"})
     * @param $id
     */
    public function activateUser($id, Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {
        $user = $userRepo->find($id);
        $user->setActif(1);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'utilisateur activé'
        );
        return $this->redirectToRoute('admin');

    }

    /**
     * @Route ("/admin/unactivateUser/{id}", name="admin_unactivateuser", methods={"GET","POST"})
     * @param $id
     */
    public function unactivateUser($id, Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {
        $user = $userRepo->find($id);
        $user->setActif(0);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'utilisateur désactivé'
        );
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/supprimer/{id}", name="admin_supprimer")
     * @param $id
     */
    public function supprimerUser($id, Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {
        $user = $userRepo->find($id);
        $pseudo = $user->getUsername();
        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "Utilisateur supprimé : " . $pseudo);
        return $this->redirectToRoute('admin');
    }

    /*
     * Appel FileUploader et permet à l'administrateur d'uploader un fichier au format csv, xml ou yaml
     */
    public function getUploadedFiles($form, $fileUploader, $slugger, $fileSerializer)
    {

        $file = $form->get('urlFile')->getData();
        if ($file) {
            try {
                $fileName = $fileUploader->upload($file, $slugger);
                $message = $fileSerializer->createUsers();

                $this->addFlash('success', 'Le fichier a bien été uploadé');
                if($message) {
                    $this->addFlash('success', $message);
                }else{
                    $this->addFlash('danger', 'L\'ajout des utilidateurs a échoué');
                }
            } catch (FileException $e) {

                $this->addFlash('danger', 'Une erreur s\'est produite');

                return $this->redirectToRoute('user_profil');
            }
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(){
        $users = $this->getDoctrine()->getRepository(User::class)-> findAll();
        return $this->render("admin/index.html.twig", [
            "users" => $users
        ]);
    }
    /**
     * @Route("/admin/creatUser", name="admin_createuser")
     */
    public function adminCreateUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setActif(true);
            $em->persist($user);
            try {
                $em->flush();
            }catch (\Exception $error){
                $this->addFlash("danger","creation impossible");
                return $this->render("Admin/adminInscription.html.twig",[
                    "form"=>$form->createView(),
                    "user"=>$user,
                ]);
            }
            $this->addFlash("success","utlisateur crée    ". $user->getUsername());
            return $this->redirectToRoute('admin');
        }
        return $this->render("Admin/adminInscription.html.twig",[
            "form"=>$form->createView(),
            "user"=>$user,
        ]);

    }

    /**
     * @Route ("/admin/{id}/modifyUser", name="admin_profiluser", methods={"GET","POST"})
     * @throws Exception
     * @param $id
     */
    public function adminEditUser(Request $request, User $user,UserPasswordEncoderInterface $encoder,EntityManagerInterface $em){
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $userPassword = $user->getPassword();
        if ($form->isSubmitted() && $form->isValid()){
            if (trim($user->getPassword())== !0){
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
            }
            else{
                $user->setPassword($userPassword);
            }
            $em->flush();
            $this->addFlash('success', 'La modification a été enregistrée');
            return $this->redirectToRoute('admin');
        }
        return  $this->render('user/edit.html.twig',[
            'user'=>$user,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/admin/ActivateUser/{id}", name="admin_activateuser", methods={"GET","POST"})
     * @param $id
     */
    public function activateUser($id,Request $request,EntityManagerInterface $em, UserRepository $userRepo){
        $user = $userRepo->find($id);
        $user->setActif(1);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success','utilisateur activé'
        );
        return $this->redirectToRoute('admin');

    }
    /**
     * @Route ("/admin/unactivateUser/{id}", name="admin_unactivateuser", methods={"GET","POST"})
     * @param $id
     */
    public function unactivateUser($id,Request $request,EntityManagerInterface $em,UserRepository $userRepo){
        $user = $userRepo->find($id);
        $user->setActif(0);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success','utilisateur désactivé'
        );
        return $this->redirectToRoute('admin');
    }
    /**
     * @Route("/admin/supprimer/{id}", name="admin_supprimer")
     * @param $id
     */
    public function supprimerUser($id,Request $request,EntityManagerInterface $em,UserRepository $userRepo){
        $user = $userRepo->find($id);
        $pseudo = $user->getUsername();
        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "Utilisateur supprimé : " . $pseudo);
        return $this->redirectToRoute('admin');
    }
}

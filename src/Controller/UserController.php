<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_inscription")
     */
    public function AddNewUser(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $user->setActif(1);
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home');

        }
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/profil", name="user_profil")
     */
    public function profilView(){
        return $this->render('user/profil.html.twig');
    }
    /**
     * @Route ("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function editProfil(Request $request, User $user){
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profil');
        }
        return  $this->render('user/edit.html.twig',[
            'user'=>$user,
            'form'=>$form->createView()
        ]);
    }


}

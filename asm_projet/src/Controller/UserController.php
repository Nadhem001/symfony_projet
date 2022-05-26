<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class UserController extends AbstractController
{
    private $UserRepository;
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine,UserRepository $UserRepository)
     {
         $this->UserRepository =$UserRepository;
        $this->entityManager = $doctrine->getManager();
    }
    /**
     * @Route("/admin/user", name="app_user")
     */
    public function index(): Response
    {
        $users = $this->UserRepository->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users'=>$users
        ]);
    }

    /**
     * @Route("/user/edit", name="edit_user")
     */
    public function edit(UserPasswordHasherInterface $passwordHasher,Request $request): Response
    {
        $user=$this->getUser();
         
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $form->get('password')->getData();
          
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);
            
            $this->entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/user/supp",name="supprime_user")
     */
    public function supprimer(Request $request)
    {
        $id = $request->get("id");
        $user = $this->UserRepository->findOneBy(["id" => $id]);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response(200);
    }








    /**
     * @Route("/verif_email", name="verif_email")
     */
    public function verif_email (Request $request){
        $email = $request->get("email");
        if($this->getUser() and $this->getUser()->getUserIdentifier() == $email){
            return new Response("404");
        }
        $user = $this->UserRepository->findOneBy(['email'=>$email]);
        if($user){
            
            return new Response("200"); 
        }
        return new Response("404");


    }
}

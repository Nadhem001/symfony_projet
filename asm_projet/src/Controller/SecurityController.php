<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    private $UserRepository;
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine,UserRepository $UserRepository)
     {
         $this->UserRepository =$UserRepository;
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       if ($this->getUser()) {
           return $this->redirectToRoute('app_home');
       }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(UserPasswordHasherInterface $passwordHasher,Request $request)
    {

        $user = new User();
        $redirect = 'app_home';
        if($request->get("id")){
            $user = $this->UserRepository->findOneBy(["id"=>$request->get("id")]);
            $redirect = "app_user";
        }

        if ($this->getUser() and !$request->get("id")) {
            return $this->redirectToRoute('app_home');
        }
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setRoles(["ROLE_USER"]);
            $password = $form->get('password')->getData();
          
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute($redirect);
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);


    }

}

<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Repository\UserRepository;
use App\Repository\LivresRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{

    private $UserRepository;
    private $entityManager;
    private $ReservationRepository;
    private $LivresRepository;

    public function __construct(ManagerRegistry $doctrine,UserRepository $UserRepository,ReservationRepository $ReservationRepository ,LivresRepository $LivresRepository)
     {
        $this->UserRepository =$UserRepository;
        $this->entityManager = $doctrine->getManager();
        $this->ReservationRepository = $ReservationRepository;
        $this->LivresRepository = $LivresRepository;
    }
    
    /**
     * @Route("/front/reservation", name="app_reservation")
     */
    public function index(): Response
    {      
        $user = $this->UserRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $reservations = $this->ReservationRepository->findBy(['user'=> $user]);
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservations'=>$reservations
        ]);
    }


    /**
     * @Route("/reservation/supp",name="supprime_reservation")
     */
    public function supprimer(Request $request)
    {
        $id = $request->get("id");
        $reservations = $this->ReservationRepository->findOneBy(["id" => $id]);

        $this->entityManager->remove($reservations);
        $this->entityManager->flush();

        return new Response(200);
    }




        /**
     * @Route("/admin/reservation", name="admin_reservation")
     */
    public function admin_reservation()
    {      
         
        $reservations = $this->ReservationRepository->findAll();
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservations'=>$reservations
        ]);
    }

    /**
     * @Route("/admin/reservation/reservation_to_emprunt", name="reservation_to_emprunt")
     */
    public function reservation_to_emprunt(Request $request)
    {      
        $id = $request->get("id");
        $reservations = $this->ReservationRepository->findOneBy(["id"=>$id]);
        $emprunt = new Emprunt();
        $emprunt->setLivre($reservations->getLivre());
        $emprunt->setUser($reservations->getUser());
        $emprunt->setDateSortie($reservations->getDateReservation());
        $emprunt->setDateRetour($reservations->getDateFin());
        $reservations->getLivre()->setDisp(1);
        
        $this->entityManager->remove($reservations);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();
        return new Response(200);
    }



      /**
     * @Route("/reservation/edit/{id}",name="edit_reservation")
     */
    
    public function edit_reservations(Request $request,int $id) 
    {   
        $livres = $this->LivresRepository->findAll();
        $users = $this->UserRepository->findAll();
        $reservations = $this->ReservationRepository->findOneBy(["id"=>$id]);
        if(!$this->getUser()){
            
            return  $this->redirectToRoute("app_login");
        }
        if(!in_array("ROLE_ADMIN",$this->getUser()->getRoles()) and $reservations->getUser()->getEmail() != $this->getUser()->getUserIdentifier()){
            return  $this->redirectToRoute("app_reservation");
        }
        if(!in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ){
            $users = $this->UserRepository->findBy(["email"=>$this->getUser()->getUserIdentifier()]);
        }
        if($request->isMethod('POST')) {
            
           if($request->get("reservation_date_debut")){
                $date_debut = new \DateTime($request->get("reservation_date_debut"));
                $reservations->setDateReservation($date_debut);
           } 
           if($request->get("reservation_date_fin")){
                $date_retour = new \DateTime($request->get("reservation_date_fin"));
                $reservations->setDateFin($date_retour);

           }
            if($request->get("liste_livre")){
                
                $livre = $this->LivresRepository->findOneBy(["id"=>$request->get("liste_livre")]);
                $reservations->setLivre($livre);
            
            }
            if($request->get("liste_user")){
                $user = $this->UserRepository->findOneBy(["id"=>$request->get("liste_user")]);
                $reservations->setUser($user);

            }

            $this->entityManager->flush();
            return $this->redirectToRoute("admin_reservation");
        }
        return $this->render('reservation/edit_reservation.html.twig', [
            'controller_name' => 'EmpruntController',
            'livres'=>$livres,
            'users'=>$users,
            'reservation'=>$reservations
        ]);
    }


}

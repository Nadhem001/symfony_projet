<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use App\Repository\ReservationRepository;
use App\Repository\LivresRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerService;
class EmpruntController extends AbstractController
{
    private $EmpruntRepository;
    private $entityManager;
    private $ReservationRepository;
    private $UserRepository;
    private $MailerService;
    private $LivresRepository;
    public function __construct(LivresRepository $LivresRepository,ReservationRepository $ReservationRepository,ManagerRegistry $doctrine,EmpruntRepository $EmpruntRepository,UserRepository $UserRepository,MailerService $MailerService )
     {
        $this->EmpruntRepository =$EmpruntRepository;
        $this->ReservationRepository =$ReservationRepository;
        $this->entityManager = $doctrine->getManager();
        $this->UserRepository = $UserRepository;
        $this->MailerService = $MailerService;
        $this->LivresRepository = $LivresRepository;
    }
    
    /**
     * @Route("/front/emprunt", name="app_emprunt")
     */
    public function index(): Response
    {   
        $user = $this->UserRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $emprunts = $this->EmpruntRepository->findBy(['user'=> $user]);
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'Livre empreunte',
            'emprunts'=>$emprunts
        ]);
    }

    /**
     * @Route("/admin/emprunt", name="admin_emprunt")
     */
    public function admin_emprunt(): Response
    {
        
        $emprunts = $this->EmpruntRepository->findAll();
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'Livre empreunte',
            'emprunts'=>$emprunts
        ]);
    }


    /**
    * @Route("/admin/emprunt/emprunt_despasser",name="emprunt_despasser")
    */
    public function emprunt_despasser()
    {  
         $date_courant = new \DateTime(date("Y-m-d"));

        $emprunts = $this->EmpruntRepository->emprunt_depasser($date_courant);
        
        $liste = $this->renderView("emprunt/liste_empreunt_depasser.html.twig",[
            "emprunts"=>$emprunts
        ]);
        return new Response($liste);

    }

    /**
     * @Route("/admin/emprunt/supp",name="supprime_emprunt")
     */
    public function supprimer(Request $request)
    {
        $id_emprunt = $request->get("id");
        $emprunt = $this->EmpruntRepository->findOneBy(["id" => $id_emprunt]);
        $emprunt->getLivre()->setDisp(0);
        $this->entityManager->remove($emprunt);
        $this->entityManager->flush();

        return new Response(200);
    }
 

    /**
     * @Route("/admin/emprunt/add_emprunt_cheked",name="add_emprunt_cheked")
     */
    public function add_emprunt_cheked(Request $request)
    {
        $emprunt = new Emprunt ();

        $date_debut = new \DateTime($request->get("emprunt_date_debut"));
        $date_retour = new \DateTime($request->get("emprunt_date_fin"));
        $livre = $this->LivresRepository->findOneBy(["id"=>$request->get("liste_livre")]);
        $user = $this->UserRepository->findOneBy(["id"=>$request->get("liste_user")]);
        $emprunt->setLivre($livre);
        $emprunt->setUser($user);
        $emprunt->setLivre($livre);

        $emprunt->setDateSortie($date_debut);

        $emprunt->setDateRetour($date_retour);
        $livre->setDisp(1);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin_emprunt");

        
    }



 
    /**
     * @Route("/admin/emprunt/mail_emprunt_depasser",name="send_mail")
     */
    public function mail_emprunt_depasser(Request $request)
    {
        $email = $request->get('email');
        $id = $request->get('id');
        $emprunt = $this->EmpruntRepository->findOneBy(["id" => $id]);
        $id_livre = $emprunt->getLivre()->getId();

        $email_to = $emprunt->getUser()->getEmail();
        $objet = "Avertissement de dépassement le date d'emprunts de livres";
        $html = "Nous avons le regret de vous informer que vous avez dépassé la période de prêt prévue.";
        $this->MailerService->sendEmail($email_to,$objet,$html);

        $emprunt->setNotifie(1);
        $this->entityManager->flush();
        return new Response(200);
    }

    /**
     * @Route("/admin/emprunt/emprunt_recuperer",name="emprunt_recuperer")
     */
    public function emprunt_recuperer(Request $request)
    {
        $id = $request->get('id');
        $emprunt = $this->EmpruntRepository->findOneBy(["id" => $id]);
      
        $livre = $emprunt->getLivre();

        $livre->setDisp(0);

        $reservations = $this->ReservationRepository->findOneBy(["livre"=>$livre]);

        $objet = "Livre disponible";

        $html = "Nous avons le plaisir de vous informer que le livre ".$livre->getTitre()." est devenu disponible pour vous";


        $this->MailerService->sendEmail($emprunt->getUser()->getEmail(),$objet,$html);

        $this->entityManager->remove($emprunt);
        $this->entityManager->flush();

        return new Response(200);
    }


        /**
     * @Route("/admin/emprunt/add",name="add_emprunt")
     */
    public function add_emprunt(Request $request)
    {   $livres = $this->LivresRepository->findBy(['disp'=>0]);
        $users = $this->UserRepository->findAll();
        return $this->render('emprunt/add_emprunt.html.twig', [
            'controller_name' => 'Livre empreunte',
            'livres'=>$livres,
            'users'=>$users
        ]);
    }

    /**
     * @Route("/admin/emprunt/edit/{id}",name="edit_emprunt")
     */
    public function edit_emprunt(Request $request,int $id) 
    {   
        $livres = $this->LivresRepository->findBy(['disp'=>0]);
        $users = $this->UserRepository->findAll();
        $emprunt = $this->EmpruntRepository->findOneBy(["id"=>$id]);

        if($request->isMethod('POST')) {
            
           if($request->get("emprunt_date_debut")){
                $date_debut = new \DateTime($request->get("emprunt_date_debut"));
                $emprunt->setDateSortie($date_debut);
           } 
           if($request->get("emprunt_date_fin")){
                $date_retour = new \DateTime($request->get("emprunt_date_fin"));
                $emprunt->setDateRetour($date_retour);

           }
            if($request->get("liste_livre")){
                
                $livre = $this->LivresRepository->findOneBy(["id"=>$request->get("liste_livre")]);
                $emprunt->setLivre($livre);
                if($emprunt->getLivre()->setDisp(0) != $request->get("liste_livre")){
                    $emprunt->getLivre()->setDisp(0);
                    $livre->setDisp(1);
                }
            
            }
            if($request->get("liste_user")){
                $user = $this->UserRepository->findOneBy(["id"=>$request->get("liste_user")]);
                $emprunt->setUser($user);

            }

            $this->entityManager->flush();
            return $this->redirectToRoute("admin_emprunt");
        }
        return $this->render('emprunt/edit_emprunt.html.twig', [
            'controller_name' => 'Livre empreunte',
            'livres'=>$livres,
            'users'=>$users,
            'emprunt'=>$emprunt
        ]);
    }



    /**
     * @Route("/admin/emprunt/liste_emprunt", name="liste_emprunt_by_user")
     */
    public function get_liste_livre_emprunt_by_user(Request $request)
    {   
        $user = $this->UserRepository->findBy(["id"=>$request->get("id")]);
        $emprunts = $this->EmpruntRepository->findBy(["user"=>$user]);
            if(!$emprunts){
                
                $nb_livre_emprunt = 0;
            }
            $nb_livre_emprunt =count($emprunts);
             
        return new Response($nb_livre_emprunt);
    }
    /**
     * @Route("/admin/emprunt/verif_emprent_livre_by_date", name="verif_emprent_livre_by_date")
     */
    public function verif_emprent_livre_by_date(Request $request){

        $id_livre = $request->get("id_livre");
        
        $date_debut = new \DateTime($request->get("date_debut"));
        $date_retour = new \DateTime($request->get("date_retour"));
        $reservation = $this->ReservationRepository->verif_disp_reservation($id_livre, $date_debut->format('Y-m-d'), $date_retour->format('Y-m-d'));

        if ($reservation) {
            return new Response("reserver");
        } else {

            $emprunt = $this->EmpruntRepository->verif_disp_emprunt($id_livre, $date_debut, $date_retour);

            if ($emprunt) {

                return new Response("emprunter");

            } else {
                return new Response(200);
            }
        }
    }
}

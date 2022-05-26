<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livres;
use App\Entity\Reservation;
use App\Form\LivresType;
use App\Repository\LivresRepository;
use App\Repository\ReservationRepository;
use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class LivresController extends AbstractController
{
    private $entityManager;
    private $Livrerepository;
    private $ReservationRepository;
    private $EmpruntRepository;

    public function __construct(
        LivresRepository $Livrerepository,
        ManagerRegistry $doctrine,
        EmpruntRepository $EmpruntRepository,
        ReservationRepository $ReservationRepository
    ) {
        $this->entityManager = $doctrine->getManager();
        $this->Livrerepository = $Livrerepository;
        $this->ReservationRepository = $ReservationRepository;
        $this->EmpruntRepository = $EmpruntRepository;
    }
    /**
     * @Route("/livres", name="app_livres")
     */
    public function index(): Response
    {
        $livres = $this->Livrerepository->findAll();
        return $this->render('livres/index.html.twig', [
            'controller_name' => 'LivresController',
            'livres' => $livres
        ]);
    }




    /**
     * @Route("/admin/livres/add", name="add_livres")
     */
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $livre = new Livres();
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $livre = $form->getData();

            $cover_livre = $form->get('path_cover')->getData();

            if ($cover_livre) {
                $originalFilename = pathinfo($cover_livre->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $cover_name = $safeFilename . '-' . uniqid() . '.' . $cover_livre->guessExtension();

                try {
                    $cover_livre->move(
                        $this->getParameter('cover_livre_path'),
                        $cover_name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $livre->setPathCover($cover_name);
            }
            $livre_path = $form->get('path_livre')->getData();

            if ($livre_path) {
                $originalFilename = pathinfo($livre_path->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $livre_path_name = $safeFilename . '-' . uniqid() . '.' . $livre_path->guessExtension();

                try {
                    $livre_path->move(
                        $this->getParameter('livre_path'),
                        $livre_path_name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $livre->setPathLivre($livre_path_name);
            }
            $livre->setSlugLivre($slugger->slug($livre->getTitre() . '' . $livre->getId()));


            $this->entityManager->persist($livre);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_livres');
        }
        return $this->render('livres/add_edit.html.twig', [
            'controller_name' => 'LivresController',
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/livres/edit/{id}", name="edit_livres")
     */

    public function edit(int $id, Request $request, SluggerInterface $slugger): Response
    {
        $livre = $this->Livrerepository->findOneBy(["id" => $id]);
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $livre = $form->getData();

            $cover_livre = $form->get('path_cover')->getData();

            if ($cover_livre) {
                $originalFilename = pathinfo($cover_livre->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $cover_name = $safeFilename . '-' . uniqid() . '.' . $cover_livre->guessExtension();

                try {
                    $cover_livre->move(
                        $this->getParameter('cover_livre_path'),
                        $cover_name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $livre->setPathCover($cover_name);
            }
            $livre_path = $form->get('path_livre')->getData();

            if ($livre_path) {
                $originalFilename = pathinfo($livre_path->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $livre_path_name = $safeFilename . '-' . uniqid() . '.' . $livre_path->guessExtension();

                try {
                    $livre_path->move(
                        $this->getParameter('livre_path'),
                        $livre_path_name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $livre->setPathLivre($livre_path_name);
            }
            $livre->setSlugLivre($slugger->slug($livre->getTitre() . '' . $livre->getId()));



            $this->entityManager->flush();
            return $this->redirectToRoute('app_livres');
        }
        return $this->render('livres/add_edit.html.twig', [
            'controller_name' => 'LivresController',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/livres/supp",name="supprime_livres")
     */
    public function supprimer(Request $request)
    {
        $id = $request->get("id");
        $livre = $this->Livrerepository->findOneBy(["id" => $id]);
        $this->entityManager->remove($livre);
        $this->entityManager->flush();
        return new Response(200);
    }

    /**
     * @Route("/livres/front/{slug}", name="slug_show_livres")
     */
    public function showLivre(string $slug)
    {

        $livre = $this->Livrerepository->findOneBy(["slug_livre" => $slug]);
        return $this->render('livres/frontLivre.html.twig', [
            'controller_name' => $livre->getTitre(),
            'livre' => $livre,
            "liste_livre_reserver_by_user"=>$this->get_liste_livre_reserver_by_user(),
        ]);
    }

    /**
     * @Route("/livres/commun/livre_commun", name="livre_commun")
     */
    public function get_livre_commun(Request $request)
    {

        $livres = $this->Livrerepository->findAll();

        $livre_commun = $this->render('livres/livre_commun.html.twig', [

            'livres' => $livres

        ]);
        return new Response($livre_commun);
    }


    /**
     * @Route("/livres/reservationlivre", name="action_livre")
    */
    public function reservation_livre_ajax(Request $request)
    {
        $id_livre = $request->get("id_livre");
        
        $date_debut = new \DateTime($request->get("date_debut"));
        $date_retour = new \DateTime($request->get("date_retour"));

        $reservation = $this->ReservationRepository->verif_disp_reservation($id_livre, $date_debut, $date_retour);

        if ($reservation) {
            return new Response("reserver");
        } else {

            $emprunt = $this->EmpruntRepository->verif_disp_emprunt($id_livre, $date_debut, $date_retour);

            if ($emprunt) {

                return new Response("emprunter");

            } else {

                $livre = $this->Livrerepository->findOneBy(["id" => $id_livre]);
 
                $reservation = new Reservation();
                $reservation->setDateReservation($date_debut);                    
                $reservation->setDateFin($date_retour);                    
                $reservation->setLivre($livre);
                $reservation->setUser($this->getUser());

                $this->entityManager->persist($reservation);
              

                $this->entityManager->flush();

                return new Response("200");
            }
        }
    }

   
    private function get_liste_livre_reserver_by_user()
    {
        $liste_livre = $this->ReservationRepository->findBy(["user"=>$this->getUser()]);
            if(!$liste_livre){
                return null;
            }
            $liste_livre_id = [];

            foreach($liste_livre as $livre){

                $liste_livre_id[] = $livre->getLivre()->getId();
            }
        return $liste_livre_id;
    }


    


}

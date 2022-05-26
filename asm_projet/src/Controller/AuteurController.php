<?php

namespace App\Controller;

use App\Entity\Auteurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AuteursRepository;
class AuteurController extends AbstractController
{
    private $entityManager;
    private $AuteursRepository;

    public function __construct(AuteursRepository $AuteursRepository,ManagerRegistry $doctrine){
        $this->entityManager = $doctrine->getManager();
        $this->AuteursRepository = $AuteursRepository;
    }
    /**
     * @Route("/auteur", name="app_auteur")
     */
    public function index(): Response
    {
        return $this->render('auteur/index.html.twig', [
            'controller_name' => 'AuteurController',
        ]);
    }





        /**
     * @Route("/auteur/add_ajax",options={"expose"=true}, name="auter_add_ajax")
     */
    public function add_ajax (Request $request){
        $nom_auteur = $request->get("nom_auteur");
        $description_auteur = $request->get("description_auteur");
        $auteur = new Auteurs;
        $auteur->setnom($nom_auteur);
        $auteur->setDescription($description_auteur);
        $this->entityManager->persist($auteur);
        $this->entityManager->flush();
       
        return new Response( $auteur->getId());       
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Livres;
use App\Entity\Auteurs;
use App\Repository\LivresRepository;
use App\Repository\AuteursRepository;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    private $entityManager;
    private $Livrerepository;
    private $AuteursRepository;

    public function __construct(AuteursRepository $AuteursRepository, LivresRepository $Livrerepository,ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->Livrerepository = $Livrerepository;
        $this->AuteursRepository = $AuteursRepository;
    }

    /**
     * @Route("/", name="app_home")
    */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $livres = $paginator->paginate(
            $this->Livrerepository->getAllLivre(), 
            
            $request->query->getInt('page', 1), 3
        );  
       
      
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Accueil',
            'livres'=>$livres
        ]);
    }
    /**
     * @Route("/livre/auteur/{id}", name="livre_auteur")
    */
    public function livre_auteur(PaginatorInterface $paginator,Request $request,int $id): Response
    {   
        $auteur = $this->AuteursRepository->findOneBy(["id"=>$id]);
        $livres = $paginator->paginate(
            $this->Livrerepository->getAllLivre($auteur->getNom()), 
            
            $request->query->getInt('page', 1), 3
        );  
       
      
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Auteur',
            'livres'=>$livres,
            'auteur'=>$auteur,
        ]);
    }
}

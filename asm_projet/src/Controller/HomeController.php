<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Livres;
use App\Repository\LivresRepository;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    private $entityManager;
    private $Livrerepository;

    public function __construct(LivresRepository $Livrerepository,ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->Livrerepository = $Livrerepository;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $livres = $paginator->paginate(
            $this->Livrerepository->getAllLivre(), 
            
            $request->query->getInt('page', 1), 1
        );  
       
      
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Livres',
            'livres'=>$livres
        ]);
    }
}

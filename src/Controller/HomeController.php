<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RetexRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(RetexRepository $retexrepo, UserRepository $userrepo)
    {   
        
        
    
        return $this->render('home/index.html.twig', [
            'retex' => $retexrepo->findlastRetex(3),
            'user'  => $userrepo->findUserWithMaxRetex()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedacteursController extends AbstractController
{
    /**
     * @Route("/redacteurs", name="redacteurs")
     */
    public function index(PaginatorInterface $paginator , Request $request, EntityManagerInterface $manager, UserRepository $UserRepo )
    {   
        $user = $paginator->paginate(
            $UserRepo->findAllVisibleQuery(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );                    
         

        return $this->render('redacteurs/index.html.twig', [
           
            'user' => $user

        ]);
    }

}

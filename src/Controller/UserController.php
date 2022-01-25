<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RetexRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index($slug,User $user, RetexRepository $Retexrepo, PaginatorInterface $paginator, EntityManagerInterface $manager, Request $request)
    {   
       
        $retex = $paginator->paginate(
            $Retexrepo->  findAllRetexQuery($slug) ,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        
        $decision = $user->getDecisions();
        $validation= $user->getValidations();

        return $this->render('user/index.html.twig', [
            'user'       => $user,
            'retex'      => $retex,
            'decision'   => $decision, 
            'validation' => $validation,
           
        ]);
    }
    /**
     * @Route("/user/front/{slug}", name="user_final")
     */
    public function UserFinal($slug,User $user, RetexRepository $Retexrepo, PaginatorInterface $paginator, EntityManagerInterface $manager, Request $request)
    {   
       
        $retex = $paginator->paginate(
            $Retexrepo-> findPublishedRetex($slug) ,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        
    
        return $this->render('user/final.html.twig', [
            'user'       => $user,
            'retex'      => $retex,
          
           
        ]);
    }
}

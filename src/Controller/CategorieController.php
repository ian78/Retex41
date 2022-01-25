<?php

namespace App\Controller;

use App\Entity\Retex;
use App\Repository\RetexRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(CategorieRepository $repo)
    {   
        $categories = $repo->findAll();
      

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categorie/{slug}" , name="categorie_show")
     * 
     * @return Response
     */
    public function show( $slug, CategorieRepository $repo, RetexRepository $Retexrepo, PaginatorInterface $paginator , Request $request, EntityManagerInterface $manager)
    {
       
        
        $categorie = $repo->findOneBySlug($slug); 
        
        
        $retex = $paginator->paginate(
            $Retexrepo-> findAllVisibleQuery($slug),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        

        return $this->render('categorie/show.html.twig', [
                'categorie' => $categorie,
                'retex'   => $retex,
        ]);

    }


}

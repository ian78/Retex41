<?php

namespace App\Controller;

use App\Form\RechercheRetexType;
use App\Repository\RetexRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $request, RetexRepository $retexRepo): Response
    {
        $retex = $retexRepo->findBy(['published' => true] , ['created_at' => 'desc']);
        
        $form = $this->createForm(RechercheRetexType::class);

        $search = $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Onrecherche les retex correspondant aux mots-clés
            
            $retex = $retexRepo->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData()
            );

        }

        return $this->render('recherche/index.html.twig', [
            'form'=>$form->createView(),
            'retex'=>$retex,
        ]);
    }
}

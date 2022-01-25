<?php

namespace App\Controller;

use App\Entity\Retex;
use App\Form\RetexType;
use App\Repository\UserRepository;
use App\Repository\RetexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRetexController extends AbstractController
{
    /**
     * @Route("/admin/retex", name="admin_retex_index")
     */
    public function index(RetexRepository $repo,PaginatorInterface $paginator , Request $request, EntityManagerInterface $manager, RetexRepository $RetexRepo )
    {

            $retex = $paginator->paginate(
                $RetexRepo-> findAllRetex2Query(),
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );      
        return $this->render('admin/retex/index.html.twig', [
            'retex'=>$retex
        ]);
    }

   


    /**
     * Permet de modifier un compte rendu
     * 
     * @Route("/admin/retex/{id}/edit" , name="admin_retex_edit")
     * 
     * 
     * 
     * @return Response
     * 
     */
    public function edit(Request $request, EntityManagerInterface $manager,PaginatorInterface $paginator , Retex $retex, RetexRepository $RetexRepo)
    { 
          

       $form = $this->createForm(RetexType::class, $retex); 

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
        
           $manager->persist($retex);
           $manager->flush();

           $this->addFlash(
               'success',
               "Le compte rendu <strong>{$retex->getTitre()}</strong> a bien été modifié en base !"
           );

             $retex = $paginator->paginate(
                $RetexRepo-> findNotVisibleQuery(),
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );      
                 
           return $this->redirectToRoute('admin_retex_index', [
            'retex'=>$retex
        ]);

           }

       return $this->render('admin/retex/edit.html.twig', [
            'retex' => $retex, 
           'form'=> $form->createView(), 
               
                   ]);
           
       }

        /**
     * 
     * Permet à un admin de supprimer un Retex
     * 
     * @Route("/retex/{id}/delete" , name="admin_retex_delete")
     * 
     
     * @return Reponse
     */
    public function delete(Retex $retex, EntityManagerInterface $manager){

       if(count($retex->getComments()) > 0 ){
           $this->addFlash(
            'warning',
            "il y'a des commentaires dans ce compte rendu vous ne pouvez pas le supprimer!"
        );
       }else{
        $manager->remove($retex);
        $manager->flush();

        $this->addFlash(
            'success',
            "le Compte-rendu <strong>{$retex->getTitre()}</strong> a été supprimez de la base !"
        );
        }
        return $this->redirectToRoute('admin_retex_index');

    }
 

   
}

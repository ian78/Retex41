<?php

namespace App\Controller;

use App\Entity\Retex;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * Permet de rédiger un nouveau compte rendu
     * 
     * @Route("/comment/new/{retex_slug}" , name="comment_new")
     *  
     * @ParamConverter("retex",   options={"mapping": {"retex_slug": "slug"}})
     * 
     * @Security("is_granted('ROLE_MANAGER') or is_granted('ROLE_DECIDEUR')")
     * @return Response
     * 
     */
    public function new(Request $request, EntityManagerInterface $manager, Retex $retex)
    {
     

       $comment = new Comment;

       $form = $this->createForm(CommentType::class, $comment); 

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

            $comment->setAuthor($this->getUser());
            $comment->setRetex($retex);
           
           $manager->persist($comment);
           $manager->flush();

           $this->addFlash(
               'success',
               "Votre avis a bien été enregitrée en base !"
           );
           $retex = $comment->getRetex();
           $categorie = $retex->getCategorie();
           
           return $this->redirectToRoute('retex_show', [
               'slug' => $retex->getSlug(),
               'categorie' =>$categorie,
               
               
           ]);
       }

       return $this->render('comment/new.html.twig', [
              
        'form'=> $form->createView(), 
             
    ]);

    }

    /**
     * Permet de modifier un compte rendu
     * 
     * @Route("/comment/{retex_slug}/edit" , name="comment_edit")
     * 
     * @ParamConverter("retex",   options={"mapping": {"retex_slug": "slug"}})
     * 
     * @Security("is_granted('ROLE_MANAGER') and user === comment.getAuthor()" , message = " Vous n'êtes pas l'auteur de ce Retex, vous ne pouvez pas le modifier ")
     * 
     * 
     * @return Response
     * 
     */
    public function edit(Request $request, EntityManagerInterface $manager,Comment $comment,  Retex $retex)
    { 
             
       $form = $this->createForm(CommentType::class, $comment); 

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
        
           $manager->persist($comment);
           $manager->flush();

           $this->addFlash(
               'success',
               "Votre avis a bien été modifié en base !"
           );

           $categorie = $retex->getCategorie();
           
           return $this->redirectToRoute('retex_show', [
               'slug' => $retex->getSlug(),
               'categorie' =>$categorie,
       
               ]);
           }

       return $this->render('comment/edit.html.twig', [
             
           'form'=> $form->createView(), 
               
                   ]);
           
       }
    


     /**
     * 
     * Permet de supprimer un commentaire
     * 
     * @Route("/comment/{retex_slug}/delete" , name="comment_delete")
     * 
     * @ParamConverter("retex",   options={"mapping": {"retex_slug": "slug"}})
     * 
     * @Security("is_granted('ROLE_MANAGER') and user === comment.getAuthor()")
     * 
     * 
     * @return Reponse
     */
    public function delete(Comment $comment, Retex $retex, EntityManagerInterface $manager){

        
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre avis a été supprimez de la base !"
        );

        $categorie = $retex->getCategorie();
           
        return $this->redirectToRoute('retex_show', [
            'slug' => $retex->getSlug(),
            'categorie' =>$categorie,
            ]);
    }

}
    



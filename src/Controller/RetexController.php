<?php

namespace App\Controller;


use Dompdf\Dompdf;
use Dompdf\Options;

use App\Entity\Retex;
use App\Form\RetexType;


use App\Entity\Categorie;
use App\Service\PjUploader;
use App\Repository\RetexRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RetexController extends AbstractController
{   
    
         
    /**
     * Permet de rédiger un nouveau compte rendu
     * 
     * @Route("/retex/newone" , name="retex_newone")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     * 
     */
     public function newone(Request $request, EntityManagerInterface $manager,PjUploader $PjUploader)
     {
      
        $user = $this->getUser();
        $retex = new Retex;

        $form = $this->createForm(RetexType::class, $retex); 

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Set de l'auteur et mise à false de la publication
            $retex->setPublished(false);
            $retex->setAuthor($this->getUser());
            
            
            //upload de la PJ
            $piecejointe = $form['piecejointe']->getData();
            
                     
            if ($piecejointe) {
                $piecejointe = $PjUploader->upload($piecejointe);
                $retex->setPiecejointe($piecejointe);
            }

              

            $manager->persist($retex);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le Draft de votre compte rendu <strong>{$retex->getTitre()}</strong> a bien été enregitrée en base !"
            );

            $categorie = $retex->getCategorie();
            
            return $this->redirectToRoute('account_index',[
                'slug' =>$user->getSlug()
                
                
            ]);
        }


        return $this->render('retex/new.html.twig', [
              
            'form'=> $form->createView(), 
                 
        ]);
     }
    /**
     * Permet de modifier un compte rendu
     * 
     * @Route("/retex/{slug}/edit" , name="retex_edit")
     * @Security("is_granted('ROLE_USER') and user === retex.getAuthor()" , message = " Vous n'êtes pas l'auteur de ce Retex, vous ne pouvez pas le modifier ")
     * 
     * 
     * @return Response
     * 
     */
     public function edit(Request $request, EntityManagerInterface $manager, Retex $retex, PjUploader $PjUploader)
     { 
          

        $form = $this->createForm(RetexType::class, $retex); 

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $piecejointe = $form['piecejointe']->getData();
                       
            if ($piecejointe) {
                $piecejointe = $PjUploader->upload($piecejointe);
                $retex->setPiecejointe($piecejointe);
            }

            $manager->persist($retex);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le compte rendu <strong>{$retex->getTitre()}</strong> a bien été modifié en base !"
            );

            $categorie = $retex->getCategorie();
            
            return $this->redirectToRoute('retex_show', [
                'slug' => $retex->getSlug(),
                'categorie' =>$categorie,
        
                ]);
            }

        return $this->render('retex/edit.html.twig', [
              
            'form'=> $form->createView(),
            'retex'=> $retex, 
                
                    ]);
            
        }
     




    /**
     * @Route("/retex/{slug}", name="retex_show")
     */
    public function show($slug, Retex $retex )
    {
        
        
        $categorie = $retex->getCategorie();

        return $this->render('retex/show.html.twig', [
            'retex' => $retex,
            'categorie' =>$categorie,
        ]);
    }


    /**
     * @Route("/retex/front/{slug}", name="retex_show_final")
     */
    public function show_final($slug, Retex $retex )
    {
        
        
        $categorie = $retex->getCategorie();

        return $this->render('retex/show_final.html.twig', [
            'retex' => $retex,
            'categorie' =>$categorie,
        ]);
    }


    /**
     * @Route("/retex/{slug}/download", name="retex_data_download")
     */
    public function RetexDownload($slug, Retex $retex)
    {
        //definition des options du PDF
        
        $pdfOptions = new Options();

        //Police par défaut

        $pdfOptions->set('defaultFont' , 'Arial');

        $pdfOptions->setIsRemoteEnabled(true);

        //instanciation de Dompdf

        $dompdf= new Dompdf($pdfOptions);
   


        $context = stream_context_create([
            'ssl'=>[
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'ammow_self_signed' => TRUE
            ]
        ]);

        $dompdf->setHttpContext($context);
        
        //génération du HTML
        $categorie = $retex->getCategorie();
        $html= $this->renderView('retex/download.html.twig', [
            'retex' => $retex,
            'categorie' =>$categorie,
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4' , 'portrait');
        $dompdf->render(); 

        //génération du fichier

        $fichier = 'retex' . '.pdf';

        //envoie le pdf au navigateur

        $dompdf->stream($fichier, [
            'Attachement' => false
        ]);

        return new Response();
    }

    /**
     * 
     * Permet de supprimer un Retex
     * 
     * @Route("/retex/{slug}/delete" , name="retex_delete")
     * 
     * @Security("is_granted('ROLE_USER') and user == retex.getAuthor()" )
     *
     * 
     * @return Reponse
     */
    public function delete(Retex $retex, EntityManagerInterface $manager){

        $categorie = $retex->getCategorie();
        $manager->remove($retex);
        $manager->flush();

        $this->addFlash(
            'success',
            "le Compte-rendu <strong>{$retex->getTitre()}</strong> a été supprimez de la base !"
        );

        return $this->redirectToRoute('categorie_show', [
            'slug' => $categorie->getSlug(),
        ]);

    }
 
}

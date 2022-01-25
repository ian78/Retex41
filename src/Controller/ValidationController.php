<?php

namespace App\Controller;

use App\Entity\Retex;
use App\Entity\Validation;
use App\Form\ValidationType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ValidationController extends AbstractController
{
    /**
     * @Route("/validation/{retex_id}", name="validation")
     * @ParamConverter("retex",      options={"mapping": {"retex_id": "id"}})
     * 
     * 
     */
    public function new(Retex $retex, Request $request,EntityManagerInterface $manager, \Swift_Mailer $mailer)
    {   

        $validation = new Validation();

        $user = $this->getUser();
        $emailFm = $user->getEmail();    

        $form = $this->createForm(ValidationType::class, $validation);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

            $valideur = $form->getData(); 
            
           
            //envoie du mail
            $message=(new \Swift_Message('Nouveau Contact'))
                    //expediteur
                ->setFrom($emailFm)
                    //destinataire
                ->setTo($validation->getValidation()->getEmail())


                // message (corps)
                ->setBody(
                    $this->renderView(
                        'validation/contact.html.twig' , compact('valideur')
                    ),
                    'text/html'
                )
            ;

            // phase d'envoie du message
            $mailer->send($message);
            $this->addFlash(  
                'success',
                "Le draft de votre compte rendu est bien était envoyé à votre valideur"
            );

                $retex->setStandby(true);
                $validation->setRetex($retex);
               

                $manager->persist($validation);

                $manager->flush();

               
                return $this->redirectToRoute('account_index',[
                    'slug' =>$user->getSlug()
                ]);
            }
        
        return $this->render('validation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/validation/show/{retex_id}", name="validation_show")
     * @ParamConverter("retex",      options={"mapping": {"retex_id": "id"}})
     * 
     */
    public function show(Retex $retex)
    {
        $user = $this->getUser();
        $validation = $retex->getValidations();
    
        return $this->render('validation/show.html.twig',[
            'slug'     =>  $user->getSlug(),
            'retex'    =>  $retex,
            'validation'=> $validation
                   
            
        ]); 
    }



}

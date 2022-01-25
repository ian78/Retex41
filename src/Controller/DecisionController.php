<?php

namespace App\Controller;

use App\Entity\Retex;
use App\Entity\Decision;
use App\Entity\Validation;
use App\Form\DecisionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DecisionController extends AbstractController
{
/**
     * @Route("/decision/{retex_id}/{validation_id}", name="decision")
     * @ParamConverter("retex",      options={"mapping": {"retex_id": "id"}})
     * @ParamConverter("validation",      options={"mapping": {"validation_id": "id"}})
     */
    public function new(Retex $retex, Validation $validation, Request $request,EntityManagerInterface $manager, \Swift_Mailer $mailer)
    {   

        $decision = new Decision();

        $user = $this->getUser();
        $emailFm = $user->getEmail();    

        $form = $this->createForm(DecisionType::class, $decision);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

            $decision = $form->getData(); 
            
           
            //envoie du mail
            $message=(new \Swift_Message('Nouveau Contact'))
                    //expediteur
                ->setFrom($emailFm)
                    //destinataire
                ->setTo($decision->getDecision()->getEmail())


                // message (corps)
                ->setBody(
                    $this->renderView(
                        'decision/contact.html.twig' , compact('decision')
                    ),
                    'text/html'
                )
            ;

            // phase d'envoie du message
            $mailer->send($message);
            $this->addFlash(  
                'success',
                "Votre avis a bien été envoyé pour décision puis publication"
            );

                
                $decision->setRetex($retex);

                $manager->remove($validation);

                $manager->persist($decision);

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
     * permet de publier en ligne un compte-rendu
     * @Route("/decision{retex_id}/{decision_id}",  name="publication")
     * 
     * @ParamConverter("retex",      options={"mapping": {"retex_id": "id"}})
     * @ParamConverter("decision",      options={"mapping": {"decision_id": "id"}})
     */
    public function publication(EntityManagerInterface $manager, Retex $retex, Decision $decision)
    {
        $user = $this->getUser();

        $retex->setPublished(true);

        $manager->remove($decision);

        $manager->flush();

        $this->addFlash(  
            'success',
            "Le compte-rendu est désormais publié"
        );

        return $this->redirectToRoute('account_index',[
            'slug' =>$user->getSlug()
        ]);

    }

}

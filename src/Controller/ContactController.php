<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isVAlid()){
            $contact = $form->getData();
          
            //envoie du mail
            $message=(new \Swift_Message('Nouveau Contact'))
                    //expediteur
                ->setFrom($contact['email'])
                    //destinataire
                ->setTo('votre@adresse.fr')

                // message (corps)
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig' , compact('contact')
                    ),
                    'text/html'
                )
            ;

            // phase d'envoie du message
            $mailer->send($message);
            
            $this->addFlash(
                'success',
                "Le message à bien été envoyé!"
            );


           return $this->redirectToRoute('homepage');

        }

        
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView() ,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Retex;
use App\Entity\Mailer;
use App\Form\MailerType;
use Symfony\Component\Mime\Email;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer/{slug}", name="mailer")
     */
    public function index($slug,UserRepository $userRepository , EntityManagerInterface $manager, Request $request, \Swift_Mailer $mailer, Retex $retex)
    {   
        
        $user = $this->getUser();
        $emailFm = $user->getEmail();    

        $form = $this->createForm(MailerType::class);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isVAlid()){
           
            
            $contact = $form->getData();         
            dd($contact);          
            //envoie du mail
            $message=(new \Swift_Message('Nouveau Contact'))
                    //expediteur
                ->setFrom($emailFm)
                    //destinataire
                ->setTo($contact['To']->getEmail())

                // message (corps)
                ->setBody(
                    $this->renderView(
                        'mailer/contact.html.twig' , compact('contact')
                    ),
                    'text/html'
                )
            ;

            // phase d'envoie du message
            $mailer->send($message);
            $this->addFlash(  
                'success',
                "Le draft de votre compte rendu est bien sauvegardé en base. Vous pourrrez le modifier à tout moment"
            );

            $this->addFlash(  
                'warning',
                " N'oubliez pas de soumettre votre compte rendu pour avis pour qu'il soit publié en ligne"
            );



           return $this->redirectToRoute('account_index',[
            'slug' =>$user->getSlug()
           ]);

        }

        
        return $this->render('mailer/index.html.twig', [
            'MailerForm' => $form->createView() ,
            
            
        ]);
    }

}

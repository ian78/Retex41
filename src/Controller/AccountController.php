<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\ResetPassType;
use App\Service\FileUploader;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use App\Repository\RetexRepository;
use Symfony\Component\Form\FormError;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountController extends AbstractController
{
    /**
     * permet de se loger
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
          
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
      
        return $this->render('account/login.html.twig', [
           
            'hasError' => $error !== null,
            'username' => $username,
        ]);
    }

    /**
     * permet de se dé logger
     * 
     * @Route("/logout" , name="account_logout")
     *
     * @return void
     */
    public function logout(){

    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register" , name ="account_register")
     * 
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, FileUploader $fileUploader )
    {

        
        $user = new User();

        $form = $this->createForm(RegistrationType::class , $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //encodage du mot de passe

            $hash =$encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);
            
            // chargement de l'avatar de l'utilisateur
            $Filename = $form['avatar']->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($Filename) {
                
                $newFilename =  $fileUploader->upload($Filename);
                $user->setFilename($newFilename);
            }

            // génération du token d'activation
            $user->setActivationToken(md5(uniqid()));

                
            $manager->persist($user);
            $manager->flush();

            //création du mail à l'adresse mail de l'utilisateur pour activer son compte

            $message=(new \Swift_Message('Activation de votre compte'))
                ->setFrom('noreply@41rt.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig' , ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                )
            ;
            // Envoie du mail
            $mailer->send($message); 

            
            $this->addFlash(
                'success',
                "Votre compte est maintenat crée ! Activez votre compte pour vous connecter sur le site"
            );
       
            return $this->redirectToRoute("account_login", []);
        }
        

        return $this->render('account/registration.html.twig' ,[
                'form' => $form->createView(),
               
        ]);

    }

    /**
     * Permet de mofifier le profil de l'utilisateur
     * 
     * @Route("/account/profile" , name="account_profile" )
     * @IsGranted("ROLE_USER")
     * 
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
       

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class , $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $Filename = $form['avatar']->getData();

            // this condition is needed because the 'avatar' field is not required
            // so the PDF file must be processed only when a file is uploaded
           
            if ($Filename) {
                
                $newFilename =  $fileUploader->upload($Filename);
                $user->setFilename($newFilename);
            }


                        
           
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profil est bien modifié !"
            );
            return $this->redirectToRoute("user_show", [
                'user' => $user,
                'slug' => $user->getSlug(),
            ]);

        }

            return $this->render('account/profile.html.twig', [
                
                'form' => $form->createView(),
            ]);

    }

    /**
     * 
     * Permet de modifier le MDP
     * 
     * @Route("/account/password-update" , name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(CategorieRepository $repo, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {

        
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class , $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //verifie que oldpassword identique à passord de user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
            //gestion d'erreur
                $form->get('oldPassword')->addError(new FormError("le mot de passe n'est pas le mot de passe actuel"));

            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe est bien modifié !"
                );

                return $this->redirectToRoute("account_login", [
                    
                ]);
            }
          
        
        }

        
        return $this->render('account/password.html.twig', [
            
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche le compte de l'utilisateur
     * 
     * @Route("/account/{slug}" , name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount($slug, RetexRepository $Retexrepo, Request $request, PaginatorInterface $paginator, EntityManagerInterface $manager, User $user )
    {   
        $retex = $paginator->paginate(
            $Retexrepo->  findAllRetexQuery($slug) ,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        $validation = $user->getValidations();
        $decision = $user->getDecisions();
           
        return $this->render('user/index.html.twig',[
            'user'          =>  $this->getUser(),
            'retex'         =>  $retex,
            'validation'    =>  $validation, 
            'decision'      =>  $decision,
           

        ]);

    }

    /**
     * @Route("/activation/{token}" , name="activation")
     *
     */
    public function activation($token, UserRepository $userRepo, EntityManagerInterface $manager){

        //vérification du token de l'utilisateur
        $user = $userRepo->findOneBy(['activation_token' => $token]);
        
        // Si l'utilisateur n'existe pas

        if(!$user){
            throw $this->createNotFoundException('cet utilisateur n\'existe pas en base');
        }
        //suppression du token
        $user->setActivationToken(null);

        $manager->persist($user);
        $manager->flush();

        //message d'information de l'utilisateur que son compte est actif
        
        $this->addFlash(
            'success',
            "Votre compte est bien activé!"
        );

        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );
    }

    /**
     * @Route("/oubli-pass" , name="app_forgotten_password")
     * 
     */
    public function forgottenPass(Request $request, UserRepository $UserRepo, \Swift_Mailer $mailer,
    TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $manager){
        //création du formulaire
        $form = $this->createForm(ResetPassType::class);
        //traitement du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $donnees =$form->getData();
            //On cherche si un user à cet email
            $user=$UserRepo->findOneByEmail($donnees['email']);
            //si l'user n'existe pas
            if(!$user){
                //envoie d'un message flash
                $this->addFlash(
                    'danger',
                    "Cette adresse mail n'existe pas"
                );
                return $this->redirectToRoute("account_login", [
                    
                    ]); 
            }
            //génération du token
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();

            }catch(\Exception $e){

                $this->addFlash(
                    'warning',
                    "Une erreure est survenue : '. $e->getMessage()"
                );
                return $this->redirectToRoute("account_login", [
                    
                    ]);

            }

            //génération URL ré initialisation du mot de passe
            $url = $this->generateUrl('app_reset_password' , ['token' => $token], 
                     UrlGeneratorInterface::ABSOLUTE_URL);

            // envoie du mail
            $message=(new \Swift_Message('Mot de passe oublié'))
            ->setFrom('noreply@41rt.fr')
            ->setTo($user->getEmail())
            ->setBody(
               "<p>Bonjour,</p><p>Une demande de ré-initialisation de votre mot de passe a été effectuée pour le site RTL.wip. 
               Veuillez cliquer sur le lien suivant : " .$url .  '</p>' ,
               'text/html'             
                )
            ;
            // Envoie du mail
            $mailer->send($message); 

            $this->addFlash(
                'success',
                "un mail de ré-initialisation vous a été envoyé"
            );

            return $this->redirectToRoute("account_login", [
                    
                ]);

        }

        //on envoie vers la page de demande de l'email
        return $this->render('account/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset-pass/{token}" , name="app_reset_password")
     */
    public function resetPassword($token, Request $request, 
    EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, UserRepository $UserRepo ){
        //on récupére l'utilisateur avec son token

        $user = $UserRepo->findOneBy(['reset_token' => $token]);

        if(!$user){
            //envoie d'un message flash
            $this->addFlash(
                'danger',
                "le token n'est pas le bon"
            );
            return $this->redirectToRoute("account_login"); 
        }
        //Si le formuleur est envoyé en méthode POST
        if($request->isMethod('POST')){
            //suppression du token de l'user
            $user->setResetToken(null);

            //chiffrelent du password
            $user->setHash($encoder->encodePassword($user, $request->request->get('password')));

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le mot de passe a été modifié avec succés"
            );
            return $this->redirectToRoute("account_login"); 
        }else{
            return $this->render('account/reset_password.html.twig', ['token'=> $token]);
        }
    }

}

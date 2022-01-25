<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;

use App\Form\AccountType;
use App\Form\AdminUserType;

use App\Repository\RoleRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user_index")
     */
    public function index(PaginatorInterface $paginator , Request $request, EntityManagerInterface $manager, UserRepository $UserRepo )
    {   
               
                    $user = $paginator->paginate(
                        $UserRepo->findAllVisibleQuery(),
                        $request->query->getInt('page', 1), /*page number*/
                        10 /*limit per page*/
                    );  
                    
                    dump($user);
                     

        return $this->render('admin/user/index.html.twig', [
            'user' => $user
            
        ]);
    }

     /**
     * Permet de modifier un compte utilisateur
     * 
     * @Route("/admin/user/{id}/edit" , name="admin_user_edit")
     * 
     * 
     * 
     * @return Response
     * 
     */
    public function edit(PaginatorInterface $paginator , Request $request, EntityManagerInterface $manager, User $user, UserRepository $UserRepo)
    { 
        

       $form = $this->createForm(AdminUserType::class, $user); 

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           
            
           $manager->persist($user);
           $manager->flush();

           $this->addFlash(
               'success',
               "L'utilisateur' <strong>{$user->getFirstName()}</strong> a bien été modifié en base !"
           );

           $user = $paginator->paginate(
            $UserRepo->findAllVisibleQuery(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );                    
         
           
        return $this->render('admin/user/index.html.twig', [
            'user' => $user
        ]);

           }

       return $this->render('admin/user/edit.html.twig', [
           'user' => $user, 
           'form'=> $form->createView(), 
               
                   ]);
           
       }

        /**
     * 
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/user/{id}/delete" , name="admin_user_delete")
     * 
     * 
     *
     * 
     * @return Reponse
     */
    public function delete(Request $request, EntityManagerInterface $manager, User $user, UserRepository $UserRepo){

       
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "l'utilisateur'<strong>{$user->getFirstName()}</strong> a été supprimez de la base !"
        );

        return $this->redirectToRoute('admin_user_index', [
            'user'=>$user
        ]);

    }
}

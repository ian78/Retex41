<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;

use App\Repository\UserRepository;

use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MailerType extends AbstractType
{   
    private $userRepository;
    
    public function __construct(UserRepository $userRepository, ?User $user)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('To' , EntityType::class , [
                'class' => User::class,
                'label' => 'A:',
                'placeholder' => 'Choisissez un valideur',
                'choices'=> $this->userRepository->findUserByRole(), 
                'choice_label'=> 'email',
                               
            ])

                      
            ->add('message' ,CKEditorType::class,[
                'label' => false,
            ])
            ->add('envoyer' , SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

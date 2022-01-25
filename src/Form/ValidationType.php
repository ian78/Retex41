<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Validation;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidationType extends AbstractType
{   
    private $userRepository;

    public function __construct(UserRepository $userRepository, ?User $user)
    {
        $this->userRepository = $userRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('validation',  EntityType::class , [
                'class' => User::class,
                'label' => 'A:',
                'placeholder' => 'Choisissez un valideur',
                'choices'=> $this->userRepository->findUserByRole(), 
                'choice_label'=> 'email',
                               
            ])

            ->add('message' ,CKEditorType::class,[
                'label' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Validation::class,
        ]);
    }
}

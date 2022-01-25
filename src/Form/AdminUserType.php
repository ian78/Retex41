<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName' , TextType::class,[
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'false'
                ]
                
            ] )
            ->add('lastName' , TextType::class,[
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'false'
                ]
            ] )
            ->add('email', EmailType::class,[
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'false'
                ]
            ] )
          
            ->add('description', TextType::class, [
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'false'
                ]
            ] )
            
            ->add('userRoles')
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

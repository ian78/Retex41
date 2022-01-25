<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RechercheRetexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots', SearchType::class,[
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez un ou plusieurs mots-clés'
                ],
                'required'=>false
            ] )

            ->add('categorie' , EntityType::class, [
                'class'=> Categorie::class,
                'label' => false,
                'attr'  =>[
                    'class' => 'form-control',
                    'placeholder' => 'filtrez par catégorie'
                    
                ],
                'required'=>false

            ])

            ->add ('Recherchez' , SubmitType::class,[
                'attr'  =>[
                    'class' => 'btn btn-primary',
                   
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

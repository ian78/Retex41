<?php

namespace App\Form;

use App\Entity\User;
use App\Form\PictureType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationType extends ApplicationType
{

     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom" , "Saisissez votre prénom" ))
            ->add('lastName' , TextType::class, $this->getConfiguration("Nom" , "Saisissez votre nom de famille" ))
            ->add('avatar', FileType::class, [
                'label' => 'Votre photo',
                
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Merci uploader une image au format jpg ou jpeg',
                    ])
                ],
            ])
            ->add('email', EmailType::class, $this->getConfiguration("Adresse mail" , "Saisissez votre adresse de messagerie" )  )
                 
            ->add('hash' , PasswordType::class , $this->getConfiguration("Mot de passe" ,  "Choisissez un mot de passe robuste"))
            ->add('passwordConfirm' , PasswordType::class , $this->getConfiguration("Confirmation" ,  "Confirmez votre mot de passe"))
            ->add('description' , TextareaType::class, $this->getConfiguration("Description" , "indiquez votre fonction, votre emploi !" ))
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

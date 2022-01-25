<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Retex;

use App\Entity\Categorie;

use App\Form\ApplicationType;
use App\Repository\UserRepository;

use Symfony\Component\Form\AbstractType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RetexType extends ApplicationType
{
 
    private $userRepository;

    public function __construct(UserRepository $userRepository, ?User $user)
    {
        $this->userRepository = $userRepository;
    }
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre' , TextType::class,[
                'label' => false,
            ] 
           )

            ->add('objet' , TextType::class,[
                'label' => ' ',
            ])

            ->add('reference', TextType::class,[
                'label' => '  ',
                
            ])

            ->add('piecejointe' , FileType::class, [
                'label' => '(Fichiers PDF)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false, 
                
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the retex details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader un document PDF valide ',
                    ])
                ],
            ])



            ->add('generalites', CKEditorType::class,[
                'label' => false,
            ])
            ->add('prepamission', CKEditorType::class ,[
                'label' => false,
            ]) 
            ->add('II1personnel',CKEditorType::class,[
                'label' => false,
            ])
            ->add('II1apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II1bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II2materiel', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II2apositif', CKEditorType::class, [
                'label' => false,
            ])
            ->add('II2bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II2cameliration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II3technique', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II3bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II3camelioration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III1personnel', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III1apositif', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('III1bperfectible', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('III1camelioration', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('III2materiel', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III2apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III2bamelioration', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('III2camelioration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IVretour', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('IV1personnel', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV1apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV1bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV1camelioration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV2materiel', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV2apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV2bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV2camelioration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV3technique', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV3apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('IV3camelioration', CKEditorType::class,[
                'label' => false,
            ])
            ->add('conclusion', CKEditorType::class,[
                'label' => false,
            ])
            ->add('II3apositif', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III2bperfectible', CKEditorType::class ,[
                'label' => false,
            ])
            ->add('IV3bperfectible', CKEditorType::class,[
                'label' => false,
            ])
            ->add('III3apositif',CKEditorType::class,[
                'label' => false,
            ])
            ->add('III3bperfectible',CKEditorType::class,[
                'label' => false,
            ])
            ->add('III3camelioration',CKEditorType::class,[
                'label' => false,
            ])
            ->add('categorie' , EntityType::class , [
                'class' => Categorie::class,
                'required' => true,
                'label' => false,
                'placeholder' => 'Choisissez une catégorie',
                ])
             
                      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Retex::class,
        ]);
    }
}

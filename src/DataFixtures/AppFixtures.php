<?php

namespace App\DataFixtures;



use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Retex;
use App\Entity\Comment;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder= $encoder;
    }

    public function load(ObjectManager $manager)
 
     {   
     $faker = Factory::create('FR-fr');


        $adminRole = new Role();
        $adminRole ->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Yann')
                  ->setLastName('Delvoye')
                  ->setEmail('ian.delvoye@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser ,'password'))
                  ->setDescription('Chef de la cellule Ops1 du bureau opérations instructution')
                  ->addUserRole($adminRole);

        $manager->persist($adminUser);


        $managerRole = new Role();
        $managerRole ->setTitle('ROLE_MANAGER');
        $manager->persist($managerRole);

        $managerUser = new User();
        $managerUser->setFirstName('cdu')
                  ->setLastName('5cie')
                  ->setEmail('cdu5@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser ,'password'))
                  ->setDescription('commandant d\'unité')
                  ->addUserRole($managerRole);

        $manager->persist($managerUser);


        // générations des utilisateurs
        

        $users = [];
        $genres = ['male' , 'female' ];

       

        for($i = 1; $i <= 10; $i++){
             $userRole = new Role();
             $userRole ->setTitle('ROLE_USER');
             $manager->persist($userRole);
            
            $user = new User();
            
            $genre = $faker->randomElement($genres);

            $picture ='https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/':'women/'). $pictureId;

            $hash = $this->encoder->encodePassword($user, 'root');

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setDescription($faker->sentence())
                 ->setHash($hash)
                 ->addUserRole($userRole);
             

            $manager->persist($user);
            $users [] = $user;
        }  
        
        for($i = 1; $i <= 6; $i++){   
        $categorie = new Categorie();

                $name = $faker->sentence(3);
                
                $categorie->setName($name);
                          
                for($j = 1 ; $j <= mt_rand(2, 20); $j++) {
                    
                        $retex =new Retex();

                        $user = $users [mt_rand(0, count($users) -1)];


                        $retex->setTitre($faker->sentence(3))
                              ->setObjet($faker->sentence(3))
                              ->setReference($faker->sentence(3))
                              ->setGeneralites($faker->paragraph(3))
                              ->setPrepamission($faker->paragraph(3))
                              ->setIV3apositif($faker->paragraph(3))
                              ->setCategorie($categorie)
                               ->setAuthor($user);

                        $manager->persist($retex);
                }

                if(mt_rand(0 , 3)){
                    $comment = new Comment();
                    
                    $comment->setContent($faker->paragraph())
                            ->setAuthor($managerUser)
                            ->setRetex($retex);

                    $manager->persist($comment);
                    
                }

            $manager->persist($categorie);
    }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

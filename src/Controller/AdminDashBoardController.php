<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashBoardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dash_board")
     */
    public function index(EntityManagerInterface $manager)
    {   
        $users = $manager->createQuery('SELECT COUNT(u) FROM APP\Entity\User u')->getSingleScalarResult();
        $retex = $manager->createQuery('SELECT COUNT(r) FROM APP\Entity\Retex r')->getSingleScalarResult();
        $validate = $manager->createQuery("SELECT COUNT(r) FROM APP\Entity\Retex r WHERE r.published = '1'")->getSingleScalarResult();
        $unvalidate = $manager->createQuery("SELECT COUNT(r) FROM APP\Entity\Retex r WHERE r.published = '0'")->getSingleScalarResult();
        
        $total = $manager->createQuery("SELECT COUNT(r) FROM APP\Entity\Retex r")->getSingleScalarResult();
       
       
       
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('users', 'retex', 'validate' , 'unvalidate', 'total')
        ]);
    }
}

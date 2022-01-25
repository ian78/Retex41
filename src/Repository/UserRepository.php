<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    private function findVisibleQuery(): QueryBuilder
    {
     
        return $this->createQueryBuilder('u');
             
    }
 
    /**
     * @return Query
     */
    public function findAllVisibleQuery()  
    {
        return $this->findVisibleQuery()
            ->getQuery()
            ;
    }

    /**
     * trouve les utilisateurs avec le role_manager
     * @return Response
     */
   
   public function findUserByRole()
   {
       return $this->createQueryBuilder('u')
                
                ->innerJoin('u.userRoles' , 'role')
                ->where('role.title like :role')
                ->setParameter('role' , 'ROLE_MANAGER')
                ->getQuery()
                ->getResult()
                    ;   
            
    }
    /**
     * trouve les utilisateurs avec le role_decideu
     * @return Response
     */
   
   public function findUserByRoleDecideur()
   {
       return $this->createQueryBuilder('u')
                
                ->innerJoin('u.userRoles' , 'role')
                ->where('role.title like :role')
                ->setParameter('role' , 'ROLE_DECIDEUR')
                ->getQuery()
                ->getResult()
                    ;   
            
    }

    public function findUserWithMaxRetex($limit = 3){

        return $this->createQueryBuilder('u')

        ->Join('u.retexes', 'r')
        ->where('r.published = true')
        ->select('u as user , COUNT(r) as sumRetex')
        ->groupBy('u')
        ->having('sumRetex > 1')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult()
        ;

    }

    
            
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

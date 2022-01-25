<?php

namespace App\Repository;

use App\Entity\Retex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Retex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Retex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Retex[]    findAll()
 * @method Retex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retex::class);
    }

     /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    private function findAllQuery(): QueryBuilder
    {
     
        return $this->createQueryBuilder('r');        
    }

  

         /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    private function findNoVisibleQuery()
    {
     
        return $this->createQueryBuilder('r')
        ->where('r.published = false');
             
    }


 
    /**
     * @return Query
     */
    public function findAllVisibleQuery($slug)  
    {
        return $this->createQueryBuilder('r')
        ->where('r.published = true')
        ->join('r.categorie' ,  'c')
        ->andwhere('c.slug = :slug')
        ->setParameter('slug' , $slug)
            ;
    }
    /**
     * @return Query
     */
    public function findAllRetex2Query()  
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.created_at' ,'DESC')
            ->getQuery()
            ->getResult()        
            ;
    }

 
    /**
     * @return Query
     */
    public function findNotVisibleQuery()  
    {
        return $this->findNoVisibleQuery()
            ->getQuery()
            ;
    }
    /**
     * @return Query
     */
    public function findAllRetexQuery($slug)  
    {
        return $this->createQueryBuilder('r')
        ->join('r.author' ,  'a')
        ->andwhere('a.slug = :slug')
        ->setParameter('slug' , $slug)
            ;
    }


    public function findlastRetex($limit)
    {
        return $this ->createQueryBuilder('r')
            ->where('r.published = true')
            ->orderBy('r.created_at' ,'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPublishedRetex($slug)
    {
        return $this->createQueryBuilder('r')
        ->where('r.published = true')
        ->join('r.author' ,  'a')
        ->andwhere('a.slug = :slug')
        ->setParameter('slug' , $slug)
        ->orderBy('r.created_at' ,'DESC')
        
        ;
    }

    /**
     * recherche les retex par mots cles
     * @return void
     * 
     */
    public function search($mots = null, $categorie = null ){
        $query = $this->createQueryBuilder('r');
        $query->where('r.published = 1');
        if($mots!= null){
            $query->andWhere('MATCH_AGAINST (r.objet, r.titre, r.generalites) AGAINST
            (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('r.categorie', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id' , $categorie);
        }

        return $query->getQuery()->getResult();
    }





    /*
    public function findOneBySomeField($value): ?Retex
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

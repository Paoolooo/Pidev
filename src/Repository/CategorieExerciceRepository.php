<?php

namespace App\Repository;

use App\Entity\CategorieExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @method CategorieExercice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieExercice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieExercice[]    findAll()
 * @method CategorieExercice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieExercice::class);
    }

    // /**
    //  * @return CategorieExercice[] Returns an array of CategorieExercice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieExercice
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

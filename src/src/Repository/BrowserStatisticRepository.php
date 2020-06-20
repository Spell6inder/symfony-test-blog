<?php

namespace App\Repository;

use App\Entity\BrowserStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BrowserStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrowserStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrowserStatistic[]    findAll()
 * @method BrowserStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrowserStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrowserStatistic::class);
    }

    public function calculatedData(){
        return $this->createQueryBuilder('b')
            ->select('b.browser', 'count(b.id) as count')
            ->groupBy('b.browser')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    // /**
    //  * @return BrowserStatistic[] Returns an array of BrowserStatistic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BrowserStatistic
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

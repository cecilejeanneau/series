<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    //    /**
    //     * @return Serie[] Returns an array of Serie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByGenreAndPopularity(string $genre)
    {   //DQL//
        $dql = "SELECT s FROM App\Entity\Serie s WHERE s.genres LIKE :genre ORDER BY s.popularity DESC";

        $em = $this->getEntityManager();

        $query = $em->createQuery($dql);
        $query->setParameter('genre', "%$genre%");
        $query->setMaxResults(10);

        //QueryBuilder
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere("s.genres LIKE :genre")->setParameter("genre", "%$genre%")->addOrderBy('s.popularity', 'DESC');

        $query = $qb->getQuery();
        $query->setMaxResults(10);

        return $query->getResult();
    }

    public
    function findWithPagination(int $page, int $limit = 50): Paginator
    {
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.seasons', 'seasons');
        $qb->addSelect('seasons');
        $qb->addOrderBy('s.popularity', 'DESC');
        $offset = ($page - 1) * $limit;
        $qb->setFirstResult($offset)->setMaxResults($limit);    // Permet de gérer les entités imbriquées
        return new Paginator($qb->getQuery());
    }
}

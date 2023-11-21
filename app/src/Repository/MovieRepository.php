<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    public function findByCategoryId($value, $limit = null): array
    {
        if(!empty($limit)) {
            return $this->createQueryBuilder('m')
                ->andWhere('m.category_id = :val')
                ->setParameter('val', $value)
                ->orderBy('m.id', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
                ;
        } else {
            return $this->createQueryBuilder('m')
                ->andWhere('m.category_id = :val')
                ->setParameter('val', $value)
                ->orderBy('m.id', 'ASC')
                ->getQuery()
                ->getResult()
                ;
        }
    }

    public function findAllTopRated($limit = null): array
    {
        $topRatedMovies = [];

        if(!empty($limit)) {
            $movies = $this->createQueryBuilder('m')
                ->orderBy('m.vote_average', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
                ;
        } else {
            $movies = $this->createQueryBuilder('m')
                ->orderBy('m.vote_average', 'DESC')
                ->getQuery()
                ->getResult()
                ;
        }

        foreach($movies as $movie) {
            if (!array_key_exists($movie->getTmdbId(), $topRatedMovies)) {
                $topRatedMovies[$movie->getTmdbId()] = $movie;
            }
        }

        return $topRatedMovies;
    }

    public function findRandom($limit): array
    {
        $randomIds = [];

        $id_limits = $this->createQueryBuilder('m')
            ->select('MIN(m.id)', 'MAX(m.id)')
            ->getQuery()
            ->getOneOrNullResult();

        for($i = 0; $i < $limit; $i++) {
            $randomIds[] = rand($id_limits[1], $id_limits[2]);
        }

        return $this->createQueryBuilder('m')
            ->where('m.id IN (:marray)')
            ->setParameter('marray', $randomIds)
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

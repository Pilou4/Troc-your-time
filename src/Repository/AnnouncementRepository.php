<?php

namespace App\Repository;

use App\Entity\Announcement;
use App\Entity\AnnouncementSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Announcement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcement[]    findAll()
 * @method Announcement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Announcement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Announcement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function getPaginate($page, $limit, $subCategories = null, $title = null, $lng = null, $lat = null, $distance = null) {
        $query = $this  ->createQueryBuilder('announce')
                        ->leftjoin('announce.subCategory', 'subCategory')
                        ->addSelect('subCategory')
                        ->where('announce.isOnline = 1')
                        ->orderBy('announce.createdAt')
                        ->setFirstResult(($page * $limit) - $limit)
                        ->setMaxResults($limit)
        ;

        if ($lat && $lng && $distance) {
            $query = $query
                ->select('announce')
                ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((announce.lat - :lat) *  pi()/180 / 2), 2) +COS(announce.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((announce.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $lng)
                ->setParameter('lat', $lat)
                ->setParameter('distance', $distance);
        }

        if ($title) {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('announce.title', ':title'),
                )
            );
            $query->setParameter('title', "%{$title}%");
        }

        if ($subCategories) {
            $query->andWhere('subCategory IN(:subCategories)')
            ->setParameter(':subCategories', array_values($subCategories));
        }

        return $query->getQuery()->getResult();
    }

    public function getTotal($subCategories = null, $title = null, $lng = null, $lat = null, $distance = null)
    {
        $query = $this  ->createQueryBuilder('announce')
                        ->leftjoin('announce.subCategory', 'subCategory')
                        ->addSelect('subCategory')
                        ->select('COUNT(announce)')
                        ->where('announce.isOnline = 1')
                    ;
        
        if ($subCategories) {
            $query->andWhere('subCategory IN(:subCategories)')
            ->setParameter(':subCategories', array_values($subCategories));
        }

        // if ($lat && $lng && $distance) {
        //     $query = $query
        //         ->select('announce')
        //         ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((announce.lat - :lat) *  pi()/180 / 2), 2) +COS(announce.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((announce.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
        //         ->setParameter('lng', $lng)
        //         ->setParameter('lat', $lat)
        //         ->setParameter('distance', $distance);
        // }

        if ($title) {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('announce.title', ':title'),
                )
            );
            $query->setParameter('title', "%{$title}%");
        }

        return $query->getQuery()->getSingleScalarResult();
    }   

    /**
     * @return Query
     */
    public function findAllVisibleQuery(AnnouncementSearch $search, $subCategories = null)
    {
        $query = $this  ->createQueryBuilder('announcement')
                        ->leftjoin('announcement.subCategory', 'subCategory')
                        ->addSelect('subCategory')
                        ->leftjoin('subCategory.category', 'category')
                        ->addSelect('category');

        if ($search->getCategory()) {
            $query->andWhere('category IN(:categories)')
            ->setParameter(':categories', $search->getCategory());
        }
                        
        if ($subCategories) {
            $query->andWhere('subCategory IN(:subCategories)')
            ->setParameter(':subCategories', array_values($subCategories));
        }
        
        if ($search->getSubCategory()) {
            $query
                ->andWhere('subCategory IN(:subCategory)')
                ->setParameter('subCategory', $search->getSubCategory());
        }

        if ($search->getLat() && $search->getLng() && $search->getDistance()) {
            $query = $query
                ->select('announcement')
                ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((announcement.lat - :lat) *  pi()/180 / 2), 2) +COS(announcement.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((announcement.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $search->getLng())
                ->setParameter('lat', $search->getLat())
                ->setParameter('distance', $search->getDistance());
        }

        if ($search->getTitle()) {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('announcement.title', ':title'),
                )
            );
            $query->setParameter('title', "%{$search->getTitle()}%");
        }

        // $query = $query->getQuery();

        return $query->getQuery()->getResult();
    }

    public function findAllOrderedByDate()
    {
        $queryBuilder = $this->createQueryBuilder('announcement');
        $queryBuilder->orderBy('announcement.createdAt', 'desc');
        $queryBuilder->setMaxResults('10');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Query
    //  */
    // public function findAllPaginate(): Query
    // {
    //     return $this->
    // }
    // /**
    //  * @return Announcement[] Returns an array of Announcement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Announcement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

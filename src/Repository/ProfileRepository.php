<?php

namespace App\Repository;

use App\Entity\Profile;
use App\Entity\ProfileSearch;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Profile $entity, bool $flush = true): void
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
    public function remove(Profile $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(ProfileSearch $search)
    {
        $query = $this  ->createQueryBuilder('profile')
                        ->leftjoin('profile.propose', 'propose')
                        ->addSelect('propose')
                        ->leftjoin('propose.category', 'category')
                        ->addSelect('category');

        if ($search->getCategory()) {
            $query->andWhere('category IN(:categories)')
            ->setParameter(':categories', $search->getCategory());
        }
        
        if ($search->getSubCategory()) {
            $query
                ->andWhere('propose IN(:subCategory)')
                ->setParameter('subCategory', $search->getSubCategory());
        }


        if ($search->getLat() && $search->getLng() && $search->getDistance()) {
            $query = $query
                ->select('profile')
                ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((profile.lat - :lat) *  pi()/180 / 2), 2) +COS(profile.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((profile.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $search->getLng())
                ->setParameter('lat', $search->getLat())
                ->setParameter('distance', $search->getDistance());
        }

        if ($search->getUsername()) {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('profile.username', ':username'),
                )
            );
            $query->setParameter('username', "%{$search->getUsername()}%");
        }
        // $query = $query->getQuery();

        return $query->getQuery()->getResult();
    }

    public function findAllOrderedByDate()
    {
        $queryBuilder = $this->createQueryBuilder('profile');
        $queryBuilder->orderBy('profile.createdAt', 'desc');
        $queryBuilder->setMaxResults('6');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    
    // /**
    //  * @return Profile[] Returns an array of Profile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

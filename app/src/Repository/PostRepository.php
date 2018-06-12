<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
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

    /**
     * @param $date
     * @param $slug
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByDateAndSlug($date, $slug): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.date = :date AND p.slug = :slug')
            ->setParameter('date', $date)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getLatest()
    {
        $q = $this->createQueryBuilder('p')->orderBy('p.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery();

        return $q->execute();
    }

}

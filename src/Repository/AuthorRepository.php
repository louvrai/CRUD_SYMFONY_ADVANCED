<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function OrederByBookNumber(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a')
              ->join('a.books','b')
            ->addSelect('b')
            ->having('count(b.id) = :val')
            ->setParameter('val', 4)



            ->getQuery()
            ->getResult()
            ;
    }

public function findAuthorsByBookCountRange($minBooks , $maxBooks)
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.nb_book >= :min_books')
        ->andWhere('a.nb_book <= :max_books')
        ->setParameter('min_books', $minBooks)
        ->setParameter('max_books', $maxBooks)

        ->getQuery()
        ->getResult();
}

    public function deleteAuthorsWithZeroBooks()
    {
        $entityManager = $this->getEntityManager();

        $dql = "DELETE FROM App\Entity\Author a WHERE a.nb_book = 0";

        $query = $entityManager->createQuery($dql);

        $query->execute();
    }
}

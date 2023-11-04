<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByRef($value = ""): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :val')
           ->setParameter('val', $value)


            ->getQuery()
           ->getResult()
        ;
   }
    public function OrederByEmailASC(): array
    {
        return $this->createQueryBuilder('b')

            ->orderBy('b.author', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function OrederByBookNumber(): array
    {
        return $this->createQueryBuilder('b')
               ->join('b.author','a')
               ->addSelect('a')
            ->where('a.nb_book = 1')
            ->andWhere('b.publicationDate < :year ')
           ->setParameter('year',new \DateTime('2023-01-01'))
            ->getQuery()
            ->getResult()
            ;
    }
    public function modificationQuery(): void
    {
        $dq= $this->getEntityManager()->createQueryBuilder();
           $dq->update('App\Entity\Book','b')
               ->set('b.category',':Romance')
               ->where('b.author = :val')
               ->setParameter('Romance','Romance')
               ->setParameter('val',54)
               ->getQuery()
               ->execute()
            ;
    }
    public function nbBookScienceFiction(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT COUNT(p.category) FROM App\Entity\Book p WHERE
         p.category LIKE :val')
            ->setParameter('val','Science Fiction');
         return $query->getSingleScalarResult();
    }
    public function listeBookByDate(){
        $entityManager = $this->getEntityManager();
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');
        $dql ='SELECT p FROM App\Entity\Book p WHERE p.publicationDate BETWEEN :start AND :end';
        $query = $entityManager->createQuery($dql)
             ->setParameter('start',$startDate)
            ->setParameter('end',$endDate);

        return $query->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
    * @return Book[] Returns an array of Book objects
    */
    public function findByIsbnField(string $value): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isbn = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array<mixed> Book[] Returns an array of Book objects
    */
    public function findOneAndReturnAsArray(string $isbn): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM book WHERE isbn = :value
            ';

        $resultSet = $conn->executeQuery($sql, ['value' => $isbn]);

        return $resultSet->fetchAllAssociative();
    }

    /**
    * @return array<mixed> Book[] Returns an array of Book objects
    */
    public function findAllAndReturnAsArray(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM book
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }
}

<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */

    public function findAllPublished(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isPublished = true')

            ->orderBy('a.id', 'DESC')

            ->getQuery()
            ->getResult()
            ;
    }

    //je fait une requete pour afficher que les article publier et fix ma requete au 2 dernieres
    public function findAllPublishedLastTwo()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isPublished = true')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(2)

            ->getQuery()
            ->getResult()
            ;
    }
// je cree une requete pour ma recherche dans les articles publier
    public function searchByTitle($search)
    {

        return $this->createQueryBuilder('a')
            // je selecttionne le titre de mes articles , s est sur les titres que je fait les recheches
            ->andWhere('a.name like :search')
            // je selectionne que les articles qui sont isPublished a true donc que sont qui sont publier
            ->andWhere('a.isPublished =true')
            // la key s est une securiter pour verifier qu il n y a pas de requete sql ou de balise php
                //'%' . $search . '%' pour que la recherche marche sur le like
            ->setParameter('search','%'. $search.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Article
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

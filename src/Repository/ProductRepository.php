<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

     /**
     * Récupère les produits avec pagination
     * 
     * @param int $page La page demandée (commence à 1)
     * @param int $limit Nombre d'éléments par page
     * @return array [produits paginés, nombre total de produits]
     */
    public function findPaginated(int $page = 1, int $limit = 9): array
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $limit);

        return [
            'products' => $paginator,
            'totalItems' => $totalItems,
            'pagesCount' => $pagesCount
        ];
    }


    public function findFeaturedProducts(int $limit = 4): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.available = :available')
            ->setParameter('available', true)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}

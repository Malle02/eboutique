<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_show')]
    public function show(string $slug, CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable.');
        }

        // $products = $productRepository->findBy(['category' => $category]);
        $categories = $categoryRepository->findAll();
        $page = $request->query->getInt('page', 1);
        $limit = 6; 

        $query = $entityManager->createQuery('
        SELECT p FROM App\Entity\Product p
        WHERE p.category = :category
        ORDER BY p.id DESC
    ')
    ->setParameter('category', $category)
    ->setMaxResults($limit)
    ->setFirstResult(($page - 1) * $limit);

    
    $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
    $totalItems = count($paginator);
    $pagesCount = ceil($totalItems / $limit);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            // 'products' => $products,
            'products' => $paginator,
            'categories' => $categories, 
            'current_category' => $category,
            'currentPage' => $page,
            'pagesCount' => $pagesCount,
            'totalItems' => $totalItems
        ]);
    }
}

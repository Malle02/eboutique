<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function list(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll(); 

        $page = $request->query->getInt('page', 1);
        $limit = 6; 
        $pagination = $productRepository->findPaginated($page, $limit);
        return $this->render('product/list.html.twig', [
            // 'products' => $products,
            'products' => $pagination['products'],
            'categories' => $categories,
            'currentPage' => $page,
            'pagesCount' => $pagination['pagesCount'],
            'totalItems' => $pagination['totalItems']
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail')]
    public function detail(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable.');
        }

       
    $relatedProducts = $productRepository->createQueryBuilder('p')
    ->where('p.category = :category')
    ->andWhere('p.id != :currentProduct')
    ->setParameter('category', $product->getCategory())
    ->setParameter('currentProduct', $product->getId())
    ->setMaxResults(4) 
    ->getQuery()
    ->getResult();


        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'related_products' => $relatedProducts, 
        ]);
    }
}

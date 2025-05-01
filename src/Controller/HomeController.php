<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        
    //     $products = $productRepository->findBy([], ['id' => 'DESC'], 4); 

    //     return $this->render('home/index.html.twig', [
    //         'products' => $products
    //     ]);
    // }


    $featuredProducts = $productRepository->findFeaturedProducts(6);
        
        $categories = $categoryRepository->findBy([], ['name' => 'ASC'], 3);

        return $this->render('home/index.html.twig', [
            'products' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}
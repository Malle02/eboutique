<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\User;
use App\Controller\Admin\UserCrudController;

class AdminDashboardController extends AbstractDashboardController
{

    

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mandingue-Chic - Admin');
    }

    public function configureMenuItems(): iterable
    {


    return [
        MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home'),
        MenuItem::linkToCrud('Produits', 'fas fa-tshirt', Product::class),
        MenuItem::linkToCrud('Catégories', 'fas fa-tags', Category::class),
        MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class),
        MenuItem::section('Utilisateurs'),
        MenuItem::linkToCrud('Clients', 'fas fa-users', User::class), 
        MenuItem::section(), 
        MenuItem::linkToRoute('Déconnexion', 'fas fa-sign-out-alt', 'admin_logout'),
        
    ];
}
}


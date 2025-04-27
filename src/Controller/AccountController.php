<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\OrderRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'account')]
    #[IsGranted('ROLE_USER')]
    public function index(OrderRepository $orderRepository)
    {
        $user = $this->getUser();
        $orders = $orderRepository->findBy(['user' => $user], ['date' => 'DESC']);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders
        ]);
    }
}

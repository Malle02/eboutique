<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderController extends AbstractController
{
    // #[Route('/order', name: 'order_checkout')]
    // #[IsGranted('ROLE_USER')]
    // public function checkout(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    // {
    //     $cart = $session->get('cart', []);

    //     if (empty($cart)) {
    //         $this->addFlash('error', 'Votre panier est vide.');
    //         return $this->redirectToRoute('cart_show');
    //     }

    //     $order = new Order();
    //     $order->setUser($this->getUser());
    //     $order->setDate(new \DateTime());
    //     $order->setStatus('En attente');
    //     $order->setOrderNumber(uniqid('MC_'));

    //     foreach ($cart as $id => $quantity) {
    //         $product = $productRepository->find($id);
    //         if ($product) {
    //             $orderItem = new OrderItem();
    //         $orderItem->setProduct($product);
    //         $orderItem->setQuantity($quantity);
    //         $orderItem->setOrder($order);
    //         $orderItem->setPrice($product->getPrice());

    //         $em->persist($orderItem);

    //         }
    //     }

    //     $em->persist($order);
    //     $em->flush();

    //     $session->remove('cart');

    //     $this->addFlash('success', 'Commande passée avec succès !');

    //     return $this->redirectToRoute('home');
    // }


    #[Route('/commande/confirmation', name: 'order_confirm')]
#[IsGranted('ROLE_USER')]
public function confirm(
    SessionInterface $session,
    ProductRepository $productRepository,
    EntityManagerInterface $em
): Response {
    $cart = $session->get('cart', []);
    if (empty($cart)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('product_list');
    }

    $order = new Order();
    $order->setUser($this->getUser());
    $order->setDate(new \DateTime());
    $order->setOrderNumber(uniqid('MC_'));
    $order->setStatus('En attente');

    $total = 0;

    foreach ($cart as $id => $quantity) {
        $product = $productRepository->find($id);

        if (!$product) continue;

        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setQuantity($quantity);
        $orderItem->setPrice($product->getPrice()); // prix unitaire

        $order->addOrderItem($orderItem);

        $em->persist($orderItem);

        $total += $product->getPrice() * $quantity;
    }

    $order->setTotal($total);
    $em->persist($order);
    $em->flush();

    $session->remove('cart');

    return $this->render('order/confirmation.html.twig', [
        'order' => $order
    ]);
}

}

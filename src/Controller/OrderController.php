<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'home_orders')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);

        $orderRepository = $entityManager->getRepository(Order::class);
        $query = $orderRepository->createQueryBuilder('o')
            ->getQuery();

        $orders = $paginator->paginate($query, $page, 10);

        $el = $this->renderView('order/index.html.twig', [
            'orders' => $orders,
        ]);

        return new Response($el);
    }
}

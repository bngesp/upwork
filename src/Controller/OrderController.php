<?php

namespace App\Controller;

use App\Domain\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private OrderService $orderService;
    public function __construct(EntityManagerInterface $entityManager, OrderService $orderService)
    {
        $this->entityManager = $entityManager;
        $this->orderService = $orderService;
    }

    #[Route('/orders', name: 'home_orders')]
    public function index(Request $request): Response
    {
        $orders = $this->orderService->searchOrders($request->query->get('search', ''), $request->query->getInt('page', 1));
        return $this->render('order/index.html.twig', ['orders' => $orders]);
    }

    #[Route('/orders/cancel/{id}', name: 'order_cancel', methods: ['POST'])]
    public function cancelOrder(int $id, Request $request ): Response
    {
        try {
            $this->orderService->cancelOrder($id);
            $this->addFlash('success', 'Commande annulée avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('home_orders');
    }
}

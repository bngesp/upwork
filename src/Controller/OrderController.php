<?php

namespace App\Controller;

use App\Domain\Entity\Order;
use App\Domain\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private OrderService $orderService;

    private SerializerInterface $serializer;

    /**
     * @param EntityManagerInterface $entityManager
     * @param OrderService $orderService
     * @param SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $entityManager, OrderService $orderService, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->orderService = $orderService;
        $this->serializer = $serializer;
    }


    #[Route('/orders', name: 'home_orders')]
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->searchOrders($request->query->get('search', ''), $request->query->getInt('page', 1));
        $jsonData = $this->serializer->serialize(
            [
                'orders' => $orders->getItems(),
                '_metadata' => [
                    'currentPage' => $orders->getCurrentPageNumber(),
                    'numItemsPerPage' => $orders->getItemNumberPerPage(),
                    'totalItems' => $orders->getTotalItemCount(),
                ]
            ],
            'json'
        );

        return new JsonResponse(json_decode($jsonData, true));
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

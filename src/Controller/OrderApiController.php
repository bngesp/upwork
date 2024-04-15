<?php

namespace App\Controller;

use App\Domain\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class OrderApiController extends AbstractController
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


    #[Route('/api/orders', name: 'orders_api_getall')]
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->searchOrders($request->query->get('search', ''), $request->query->getInt('page', 1));
        $jsonData = $this->serializer->serialize(
            [
                'orders' => $orders->getItems(),
                'pagination' => [
                    'currentPage' => $orders->getCurrentPageNumber(),
                    'numItemsPerPage' => $orders->getItemNumberPerPage(),
                    'totalItems' => $orders->getTotalItemCount(),
                ]
            ],
            'json'
        );
        $el = new JsonResponse(json_decode($jsonData, true));
        $el->headers->set('Access-Control-Allow-Origin', '*');
        return $el;
    }

    // api for cancel order by id
    #[Route('/api/orders/cancel/{id}', name: 'order_cancel', methods: ['POST'])]
    public function cancelOrder(int $id, Request $request ): JsonResponse
    {
        try {
            $this->orderService->cancelOrder($id);
            return new JsonResponse(['message' => 'Command canceled'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}

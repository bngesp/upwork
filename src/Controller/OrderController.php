<?php

namespace App\Controller;

use App\Domain\Entity\Order;
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

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/cancel/{id}', name: 'order_cancel', methods: ['POST'])]
    public function cancelOrder(int $id, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->redirectToRoute('order_index')->with('error', 'Commande non trouvée');
        }
        $order->setStatus('cancelled');
        $entityManager->flush();

        return $this->redirectToRoute('order_index')->with('success', 'Commande annulée avec succès');
    }
}

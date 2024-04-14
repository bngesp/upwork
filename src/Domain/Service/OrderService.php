<?php
/**
 * OrderService.php
 * create by bngesp
 * create at 14/04/2024 on project upwork-task
 * visite https://github.com/bngesp for more core
 */

namespace App\Domain\Service;

use App\Domain\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private OrderRepository $orderRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, OrderService $orderService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->orderService = $orderService;
    }

    public function cancelOrder(int $id): void
    {
        $order = $this->orderRepository->find($id);

        if ($order) {
            $order->setStatus('cancelled');
            $this->entityManager->flush();
        } else {
            throw new \Exception('Commande non trouvée');
        }
    }

}
<?php
/**
 * OrderService.php
 * create by bngesp
 * create at 14/04/2024 on project upwork-task
 * visite https://github.com/bngesp for more core
 */

namespace App\Domain\Service;

use AllowDynamicProperties;
use App\Domain\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

#[AllowDynamicProperties]
class OrderService
{
    private OrderRepository $orderRepository;
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;

    public function __construct(OrderRepository $orderRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
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

    public function searchOrders(string $search, int $page): PaginationInterface
    {
        $queryBuilder = $this->orderRepository->createQueryBuilder('o');
        if ($search) {
            $queryBuilder
                ->where('o.customer LIKE :search OR o.status LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $query = $queryBuilder->getQuery();
        return $this->paginator->paginate($query, $page, 10);
    }

}
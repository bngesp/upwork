<?php
/**
 * OrderService.php
 * create by bngesp
 * create at 14/04/2024 on project upwork-task
 * visite https://github.com/bngesp for more core
 */

namespace App\Domain\Service;

use AllowDynamicProperties;
use App\Domain\Constant\OrderConstant;
use App\Entity\Order;
use App\Repository\OrderRepository;
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
            throw new \Exception('Command not found');
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

    public static function getNewOrder($data): ?Order
    {
        $order = new Order();
        $order->setDate(new \DateTime($data[OrderConstant::DATE]));
        $order->setCustomer($data[OrderConstant::CUSTOMER]);
        $order->setAddress1($data[OrderConstant::ADDRESS1]);
        $order->setCity($data[OrderConstant::CITY]);
        $order->setPostcode($data[OrderConstant::POSTCODE]);
        $order->setCountry($data[OrderConstant::COUNTRY]);
        $order->setAmount($data[OrderConstant::AMOUNT]);
        $order->setStatus($data[OrderConstant::STATUS]);
        $order->setDeleted($data[OrderConstant::DELETED]);
        $order->setLastModified(new \DateTime($data[OrderConstant::LASTMODIFIED]));
        return $order;
    }

}
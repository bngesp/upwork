<?php

namespace App\Command;

use App\Domain\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'import:orders',
    description: 'command to import orders from a json file',
)]
class ImportOrdersCommand extends Command
{
   private EntityManagerInterface $entityManager;
   private KernelInterface $kernel;

   public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
   {
       $this->entityManager = $entityManager;
       $this->kernel = $kernel;
       parent::__construct();
   }

   protected function execute(InputInterface $input, OutputInterface $output): int
   {
       $fileOrders = $this->kernel->getProjectDir() . '/data/orders.json';
       $data = json_decode(file_get_contents($fileOrders), true);
       if($data === null) {
           $output->writeln('Error reading file');
           return Command::FAILURE;
       }

       array_map(function($order) {
           $order = $this->initOrder($order);
           $this->entityManager->persist($order);
       }, $data);

         $this->entityManager->flush();
         $output->writeln('Orders imported successfully');
         return Command::SUCCESS;
   }

    /**
     * @throws \Exception
     */
    private function initOrder($data): ?Order
   {
       $order = new Order();
       $order->setDate(new \DateTime($data['date']));
       $order->setCustomer($data['customer']);
       $order->setAddress1($data['address1']);
       $order->setCity($data['city']);
       $order->setPostcode($data['postcode']);
       $order->setCountry($data['country']);
       $order->setAmount($data['amount']);
       $order->setStatus($data['status']);
       $order->setDeleted($data['deleted']);
       $order->setLastModified(new \DateTime($data['last_modified']));

       return $order;
   }
}

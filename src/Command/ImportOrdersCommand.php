<?php

namespace App\Command;

use App\Domain\Service\OrderService;
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
       parent::__construct();
       $this->entityManager = $entityManager;
       $this->kernel = $kernel;
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
           $order = OrderService::getNewOrder($order);
           $this->entityManager->persist($order);
       }, $data);

         $this->entityManager->flush();
         $output->writeln('Orders imported successfully');
         return Command::SUCCESS;
   }

}

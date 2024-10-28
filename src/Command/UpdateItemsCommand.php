<?php

declare(strict_types=1);

namespace WolfShop\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WolfShop\Entity\ItemEntity;
use WolfShop\Service\ItemUpdater;

#[AsCommand(
    name: 'wolfshop:update-items',
    description: 'Update all items.',
)]
final class UpdateItemsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ItemUpdater $itemUpdater
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $itemRepository = $this->entityManager->getRepository(ItemEntity::class);
        $items = $itemRepository->findAll();

        foreach ($items as $item) {
            $this->itemUpdater->update($item);
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();

        $io->info('All items have been successfully updated.');

        return Command::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace WolfShop\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WolfShop\Service\ItemImportService;

#[AsCommand(
    name: 'wolfshop:import-items',
    description: 'Imports items from the external API.'
)]
final class ImportItemsCommand extends Command
{
    public function __construct(
        private ItemImportService $itemImportService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import Items');
        $io->info('Starting the import process from the external API...');

        try {
            // Use the service to import items
            $importedCount = $this->itemImportService->importItems();
            if ($importedCount === 0) {
                $io->info('No items to import.');
            } else {
                $io->success(sprintf('Successfully imported %d items.', $importedCount));
            }
        } catch (\Exception $e) {
            $io->error('An error occurred during the import process: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

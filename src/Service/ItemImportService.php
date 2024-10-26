<?php

declare(strict_types=1);

namespace WolfShop\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use WolfShop\DTO\ApiItemDTO;
use WolfShop\Entity\ItemEntity;
use WolfShop\Exception\ApiClientException;
use WolfShop\Factory\ItemFactory;
use WolfShop\Repository\ItemRepository;

final class ItemImportService
{
    public function __construct(
        private readonly ExternalItemApiClient $externalItemApiClient,
        private readonly ItemRepository $itemRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ItemFactory $itemFactory,
        private readonly LoggerInterface $logger,
        private readonly ItemUpdater $itemUpdater,
    ) {
    }

    /**
     * Imports items from the external API, updating existing items or creating new ones.
     *
     * @return int Number of items imported.
     * @throws ApiClientException
     */
    public function importItems(): int
    {
        $itemsData = $this->externalItemApiClient->fetchItems();

        if (! $itemsData) {
            return 0;
        }

        $existingItemsByName = $this->findExistingItemsByName($itemsData);
        $importedCount = $this->processItems($itemsData, $existingItemsByName);

        $this->entityManager->flush();

        return $importedCount;
    }

    /**
     * Maps existing items by name for efficient lookup.
     *
     * @param ApiItemDTO[] $itemsData
     *
     * @return ItemEntity[]
     */
    private function findExistingItemsByName(array $itemsData): array
    {
        $itemNames = array_map(fn ($item) => $item->getName(), $itemsData);
        $existingItems = $this->itemRepository->findByNames($itemNames);

        return array_reduce(
            $existingItems,
            fn (array $carry, ItemEntity $item) => $carry + [
                $item->getName() => $item,
            ],
            []
        );
    }

    /**
     * Processes items by updating existing items or creating new ones.
     *
     * @param ApiItemDTO[] $itemsData
     * @param ItemEntity[] $existingItemsByName
     *
     * @return int Number of items processed.
     */
    private function processItems(array $itemsData, array &$existingItemsByName): int
    {
        $importedCount = 0;
        foreach ($itemsData as $itemDTO) {
            $isProcessed = $this->processItem($itemDTO, $existingItemsByName);
            $importedCount += $isProcessed ? 1 : 0;
        }

        return $importedCount;
    }

    /**
     * Processes a single item, updating or creating it as necessary.
     * Updates multiple instances of an item if it reappears.
     *
     * @param ItemEntity[] $existingItemsByName
     *
     * @return bool Whether the item was processed successfully.
     */
    private function processItem(ApiItemDTO $itemDTO, array &$existingItemsByName): bool
    {
        try {
            $name = $itemDTO->getName();
            if (isset($existingItemsByName[$name])) {
                $this->updateExistingItem($existingItemsByName[$name]);
            } else {
                $newItem = $this->itemFactory->createItemEntityFromApiDTO($itemDTO);
                $this->entityManager->persist($newItem);
                $existingItemsByName[$name] = $newItem;
            }

            return true;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error processing item "%s": %s', $itemDTO->getName(), $e->getMessage()));
            return false;
        }
    }

    /**
     * Updates the existing item according to the defined strategy.
     */
    private function updateExistingItem(ItemEntity $itemEntity): void
    {
        $this->itemUpdater->update($itemEntity, updateSellIn: false);
        $this->entityManager->persist($itemEntity);
    }
}

<?php

declare(strict_types=1);

namespace WolfShop\Service;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use WolfShop\Entity\ItemEntity;
use WolfShop\Strategy\ItemUpdateStrategyInterface as StrategyItemUpdateStrategyInterface;

class ItemUpdater
{
    /**
     * @var array<string, StrategyItemUpdateStrategyInterface>
     */
    private array $strategies = [];

    /**
     * @param iterable<StrategyItemUpdateStrategyInterface> $strategies
     */
    public function __construct(
        #[AutowireIterator(
            tag: 'wolfshop.item_strategy',
            defaultIndexMethod: 'getCategory'
        )] iterable $strategies
    ) {
        foreach ($strategies as $strategy) {
            $category = $strategy->getCategory();
            $this->strategies[$category] = $strategy;
        }
    }

    /**
     * Update SellIn and Quality of an item.
     */
    public function update(ItemEntity $itemEntity, bool $updateSellIn = true): void
    {
        $category = (string) $itemEntity->getCategory()->value;
        $strategy = $this->strategies[$category];

        // Convert ItemEntity to Item
        $item = $itemEntity->toItem();

        // Update Item using the strategy
        $strategy->update($item, $updateSellIn);

        // Map updated values back to ItemEntity
        $itemEntity->setSellIn($item->sellIn);
        $itemEntity->setQuality($item->quality);
    }
}

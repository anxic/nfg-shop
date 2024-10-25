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

    public function update(ItemEntity $itemEntity, bool $updateSellIn = true): void
    {
        $category = $itemEntity->getCategory();
        $strategy = $this->strategies[$category->value];

        // Convert ItemEntity to Item
        $item = $itemEntity->toItem();

        // Update Item using the strategy
        $strategy->update($item);

        // Map updated values back to ItemEntity
        if ($updateSellIn) {
            $itemEntity->setSellIn($item->sellIn);
        }
        $itemEntity->setQuality($item->quality);
    }
}

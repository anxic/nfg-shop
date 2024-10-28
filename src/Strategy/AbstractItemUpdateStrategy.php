<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Item;

abstract class AbstractItemUpdateStrategy implements ItemUpdateStrategyInterface
{
    public const MAX_QUALITY = 50;

    public const MIN_QUALITY = 0;

    public function decreaseSellIn(Item $item): void
    {
        $item->sellIn--;
    }

    protected function increaseQuality(Item $item, int $amount = 1): void
    {
        $item->quality = min($item->quality + $amount, self::MAX_QUALITY);
    }

    protected function decreaseQuality(Item $item, int $amount = 1): void
    {
        $item->quality = max($item->quality - $amount, self::MIN_QUALITY);
    }

    protected function isExpired(Item $item): bool
    {
        return $item->sellIn < 0;
    }
}

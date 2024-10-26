<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class LegendaryItemStrategy extends AbstractItemUpdateStrategy
{
    public const LEGENDARY_QUALITY = 80;

    public function update(Item $item, bool $updateSellIn = false): void
    {
        $item->quality = self::LEGENDARY_QUALITY;
    }

    public static function getCategory(): string
    {
        return ItemCategoryEnum::Legendary->value;
    }
}

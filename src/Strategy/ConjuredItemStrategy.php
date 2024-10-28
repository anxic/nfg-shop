<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class ConjuredItemStrategy extends AbstractItemUpdateStrategy
{
    public function update(Item $item, bool $updateSellIn = true): void
    {
        $this->decreaseQuality($item, 2);
        if ($updateSellIn) {
            $this->decreaseSellIn($item);
        }

        if ($this->isExpired($item)) {
            $this->decreaseQuality($item, 2);
        }
    }

    public static function getCategory(): string
    {
        return ItemCategoryEnum::Conjured->value;
    }
}

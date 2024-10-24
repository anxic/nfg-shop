<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class ConjuredItemStrategy extends AbstractItemUpdateStrategy
{
    public function update(Item $item): void
    {
        $this->decreaseQuality($item, 2);
        $this->decreaseSellIn($item);

        if ($this->isExpired($item)) {
            $this->decreaseQuality($item, 2);
        }
    }

    public function getCategory(): ItemCategoryEnum
    {
        return ItemCategoryEnum::Conjured;
    }
}

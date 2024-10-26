<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class AgedItemStrategy extends AbstractItemUpdateStrategy
{
    public function update(Item $item, bool $updateSellIn = true): void
    {
        $this->increaseQuality($item);

        if ($updateSellIn) {
            $this->decreaseSellIn($item);
        }

        if ($this->isExpired($item)) {
            $this->increaseQuality($item);
        }
    }

    public static function getCategory(): string
    {
        return ItemCategoryEnum::Aged->value;
    }
}

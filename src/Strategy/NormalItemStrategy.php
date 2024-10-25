<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class NormalItemStrategy extends AbstractItemUpdateStrategy
{
    public function update(Item $item): void
    {
        $this->decreaseQuality($item);
        $this->decreaseSellIn($item);

        if ($this->isExpired($item)) {
            $this->decreaseQuality($item);
        }
    }

    public static function getCategory(): string
    {
        return ItemCategoryEnum::Normal->value;
    }
}

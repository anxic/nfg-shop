<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

class BackstagePassStrategy extends AbstractItemUpdateStrategy
{
    public function update(Item $item): void
    {
        if ($item->sellIn > 10) {
            $this->increaseQuality($item);
        } elseif ($item->sellIn > 5) {
            $this->increaseQuality($item, 2);
        } elseif ($item->sellIn > 0) {
            $this->increaseQuality($item, 3);
        } else {
            $item->quality = self::MIN_QUALITY;
        }

        $this->decreaseSellIn($item);
    }

    public function getCategory(): ItemCategoryEnum
    {
        return ItemCategoryEnum::BackstagePass;
    }
}

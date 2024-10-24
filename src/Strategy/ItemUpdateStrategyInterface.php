<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Item;

interface ItemUpdateStrategyInterface
{
    public function update(Item $item): void;

    public function getCategory(): ItemCategoryEnum;
}

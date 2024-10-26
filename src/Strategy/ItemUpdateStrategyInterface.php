<?php

declare(strict_types=1);

namespace WolfShop\Strategy;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use WolfShop\Item;

#[AutoconfigureTag('wolfshop.item_strategy')]
interface ItemUpdateStrategyInterface
{
    public function update(Item $item, bool $updateSellIn = true): void;

    public static function getCategory(): string;
}

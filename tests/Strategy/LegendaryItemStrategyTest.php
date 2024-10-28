<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\Strategy\LegendaryItemStrategy;

class LegendaryItemStrategyTest extends TestCase
{
    private const ITEM_NAME = 'Samsung Galaxy S23';

    public function testQualityAndSellInDoNotChange(): void
    {
        $item = new Item(self::ITEM_NAME, 5, 80);
        $strategy = new LegendaryItemStrategy();
        $strategy->update($item);

        $this->assertSame(80, $item->quality);
        $this->assertSame(5, $item->sellIn);
    }
}

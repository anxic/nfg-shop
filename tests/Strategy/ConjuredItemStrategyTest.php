<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\Strategy\ConjuredItemStrategy;

class ConjuredItemStrategyTest extends TestCase
{
    private const ITEM_NAME = 'Xiaomi Redmi Note 13';

    public function testUpdateBeforeSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 3, 6);
        $strategy = new ConjuredItemStrategy();
        $strategy->update($item);

        $this->assertSame(4, $item->quality);
        $this->assertSame(2, $item->sellIn);
    }

    public function testUpdateAfterSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 0, 6);
        $strategy = new ConjuredItemStrategy();
        $strategy->update($item);

        $this->assertSame(2, $item->quality);
        $this->assertSame(-1, $item->sellIn);
    }

    public function testQualityDoesNotGoNegative(): void
    {
        $item = new Item(self::ITEM_NAME, 0, 3);
        $strategy = new ConjuredItemStrategy();
        $strategy->update($item);

        $this->assertSame(0, $item->quality);
        $this->assertSame(-1, $item->sellIn);
    }
}

<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\Strategy\AgedItemStrategy;

class AgedItemStrategyTest extends TestCase
{
    private const ITEM_NAME = 'Apple AirPods';

    public function testUpdateBeforeSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 10, 20);
        $strategy = new AgedItemStrategy();
        $strategy->update($item);

        $this->assertSame(21, $item->quality);
        $this->assertSame(9, $item->sellIn);
    }

    public function testUpdateAfterSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 0, 20);
        $strategy = new AgedItemStrategy();
        $strategy->update($item);

        $this->assertSame(22, $item->quality);
        $this->assertSame(-1, $item->sellIn);
    }

    public function testQualityDoesNotExceedMax(): void
    {
        $item = new Item(self::ITEM_NAME, 5, 50);
        $strategy = new AgedItemStrategy();
        $strategy->update($item);

        $this->assertSame(50, $item->quality);
        $this->assertSame(4, $item->sellIn);
    }
}

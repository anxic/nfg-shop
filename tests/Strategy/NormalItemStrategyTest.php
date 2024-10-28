<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\Strategy\NormalItemStrategy;

class NormalItemStrategyTest extends TestCase
{
    private const ITEM_NAME = 'elePHPant';

    public function testUpdateBeforeSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 10, 20);
        $strategy = new NormalItemStrategy();
        $strategy->update($item);

        $this->assertSame(19, $item->quality);
        $this->assertSame(9, $item->sellIn);
    }

    public function testUpdateAfterSellIn(): void
    {
        $item = new Item(self::ITEM_NAME, 0, 20);
        $strategy = new NormalItemStrategy();
        $strategy->update($item);

        $this->assertSame(18, $item->quality);
        $this->assertSame(-1, $item->sellIn);
    }

    public function testQualityDoesNotGoNegative(): void
    {
        $item = new Item(self::ITEM_NAME, 5, 0);
        $strategy = new NormalItemStrategy();
        $strategy->update($item);

        $this->assertSame(0, $item->quality);
        $this->assertSame(4, $item->sellIn);
    }
}

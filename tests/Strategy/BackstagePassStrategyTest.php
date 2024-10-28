<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\Strategy\BackstagePassStrategy;

class BackstagePassStrategyTest extends TestCase
{
    private const ITEM_NAME = 'Apple iPad Air';

    public function testUpdateMoreThan10Days(): void
    {
        $item = new Item(self::ITEM_NAME, 15, 20);
        $strategy = new BackstagePassStrategy();
        $strategy->update($item);

        $this->assertSame(21, $item->quality);
        $this->assertSame(14, $item->sellIn);
    }

    public function testUpdateBetween6And10Days(): void
    {
        $item = new Item(self::ITEM_NAME, 10, 20);
        $strategy = new BackstagePassStrategy();
        $strategy->update($item);

        $this->assertSame(22, $item->quality);
        $this->assertSame(9, $item->sellIn);
    }

    public function testUpdateBetween1And5Days(): void
    {
        $item = new Item(self::ITEM_NAME, 5, 20);
        $strategy = new BackstagePassStrategy();
        $strategy->update($item);

        $this->assertSame(23, $item->quality);
        $this->assertSame(4, $item->sellIn);
    }

    public function testUpdateOnSellInZero(): void
    {
        $item = new Item(self::ITEM_NAME, 0, 20);
        $strategy = new BackstagePassStrategy();
        $strategy->update($item);

        $this->assertSame(0, $item->quality);
        $this->assertSame(-1, $item->sellIn);
    }

    public function testQualityDoesNotExceedMax(): void
    {
        $item = new Item(self::ITEM_NAME, 5, 49);
        $strategy = new BackstagePassStrategy();
        $strategy->update($item);

        $this->assertSame(50, $item->quality);
        $this->assertSame(4, $item->sellIn);
    }
}

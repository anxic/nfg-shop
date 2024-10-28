<?php

declare(strict_types=1);

namespace WolfShop\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Entity\ItemEntity;
use WolfShop\Service\ItemUpdater;
use WolfShop\Strategy\AgedItemStrategy;
use WolfShop\Strategy\BackstagePassStrategy;
use WolfShop\Strategy\ConjuredItemStrategy;
use WolfShop\Strategy\LegendaryItemStrategy;
use WolfShop\Strategy\NormalItemStrategy;

class ItemUpdaterTest extends TestCase
{
    private ItemUpdater $itemUpdater;

    protected function setUp(): void
    {
        // Créez des instances des stratégies
        $strategies = [
            new NormalItemStrategy(),
            new AgedItemStrategy(),
            new BackstagePassStrategy(),
            new LegendaryItemStrategy(),
            new ConjuredItemStrategy(),
        ];

        // Simulez le comportement de l'attribut AutowireIterator
        $this->itemUpdater = new ItemUpdater($strategies);
    }

    public function testUpdateNormalItem(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName('elePHPant')
            ->setSellIn(10)
            ->setQuality(20)
            ->setCategory(ItemCategoryEnum::Normal);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(9, $itemEntity->getSellIn());
        $this->assertSame(19, $itemEntity->getQuality());
    }

    public function testUpdateAgedItem(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName('Apple AirPods')
            ->setSellIn(2)
            ->setQuality(0)
            ->setCategory(ItemCategoryEnum::Aged);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(1, $itemEntity->getSellIn());
        $this->assertSame(1, $itemEntity->getQuality());
    }

    public function testUpdateBackstagePass(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName('Apple iPad Air')
            ->setSellIn(15)
            ->setQuality(20)
            ->setCategory(ItemCategoryEnum::BackstagePass);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(14, $itemEntity->getSellIn());
        $this->assertSame(21, $itemEntity->getQuality());
    }

    public function testUpdateLegendaryItem(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName('Samsung Galaxy S23')
            ->setSellIn(0)
            ->setQuality(80)
            ->setCategory(ItemCategoryEnum::Legendary);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(0, $itemEntity->getSellIn());
        $this->assertSame(80, $itemEntity->getQuality());
    }

    public function testUpdateConjuredItem(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName('Xiaomi Redmi Note 13')
            ->setSellIn(3)
            ->setQuality(6)
            ->setCategory(ItemCategoryEnum::Conjured);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(2, $itemEntity->getSellIn());
        $this->assertSame(4, $itemEntity->getQuality());
    }

    public function testUpdateExpiredItem(): void
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName(name: 'Nokia 3310')
            ->setSellIn(0)
            ->setQuality(10)
            ->setCategory(ItemCategoryEnum::Normal);

        $this->itemUpdater->update($itemEntity);

        $this->assertSame(-1, $itemEntity->getSellIn());
        $this->assertSame(8, $itemEntity->getQuality());
    }
}

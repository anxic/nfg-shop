<?php

declare(strict_types=1);

namespace WolfShop\Factory;

use WolfShop\DTO\ApiItemDTO;
use WolfShop\Entity\ItemCategoryEnum;
use WolfShop\Entity\ItemEntity;

class ItemFactory
{
    /**
     * Creates an ItemEntity from an ApiItemDTO.
     */
    public function createItemEntityFromApiDTO(ApiItemDTO $itemDTO): ItemEntity
    {
        $itemEntity = new ItemEntity();
        $itemEntity->setName($itemDTO->getName());
        $itemEntity->setSellIn($itemDTO->getSellIn());
        $itemEntity->setQuality($itemDTO->getQuality());
        $itemEntity->setCategory($this->getCategoryByName($itemDTO->getName()));

        return $itemEntity;
    }

    /**
     * Determines the item category based on its name.
     */
    public function getCategoryByName(string $name): ItemCategoryEnum
    {
        $nameLower = mb_strtolower($name);

        return match (true) {
            $nameLower === 'apple airpods' => ItemCategoryEnum::Aged,
            $nameLower === 'apple ipad air' => ItemCategoryEnum::BackstagePass,
            $nameLower === 'xiaomi redmi note 13' => ItemCategoryEnum::Conjured,
            $nameLower === 'samsung galaxy s23' => ItemCategoryEnum::Legendary,
            default => ItemCategoryEnum::Normal,
        };
    }
}

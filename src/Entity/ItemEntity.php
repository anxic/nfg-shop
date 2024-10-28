<?php

declare(strict_types=1);

namespace WolfShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use WolfShop\Item;
use WolfShop\Strategy\LegendaryItemStrategy;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class ItemEntity
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sellIn;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quality;

    // In case of we need to query about the url from the image
    // I recommend to use a separate table for the images
    /**
     * @var array<string, string>|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $imageData = null;

    #[ORM\Column(type: Types::STRING, enumType: ItemCategoryEnum::class)]
    private ItemCategoryEnum $category;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSellIn(): int
    {
        return $this->sellIn;
    }

    public function setSellIn(int $sellIn): self
    {
        $this->sellIn = $sellIn;

        return $this;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function setQuality(int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return array<string, string>|null
     */
    public function getImageData(): ?array
    {
        return $this->imageData;
    }

    /**
     * @param array<string, string>|null $imageData
     */
    public function setImageData(?array $imageData): self
    {
        $this->imageData = $imageData;
        return $this;
    }

    public function getCategory(): ItemCategoryEnum
    {
        return $this->category;
    }

    public function setCategory(ItemCategoryEnum $category): self
    {
        $this->category = $category;

        if ($category === ItemCategoryEnum::Legendary) {
            $this->setQuality(LegendaryItemStrategy::LEGENDARY_QUALITY);
        }

        return $this;
    }

    public function toItem(): Item
    {
        $item = new Item($this->name, $this->sellIn, $this->quality);
        if ($this->getImgUrl() !== null) {
            $item->setImgUrl($this->getImgUrl());
        }

        return $item;
    }

    public static function fromItem(Item $item): self
    {
        $entity = new self();
        $entity->name = $item->name;
        $entity->sellIn = $item->sellIn;
        $entity->quality = $item->quality;

        return $entity;
    }

    private function getImgUrl(): ?string
    {
        return $this->getImageData()['url'] ?? null;
    }
}

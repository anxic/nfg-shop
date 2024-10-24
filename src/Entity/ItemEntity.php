<?php

declare(strict_types=1);

namespace WolfShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use WolfShop\Item;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class ItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sellIn;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quality;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $imgUrl = null;

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

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function toItem(): Item
    {
        return new Item($this->name, $this->sellIn, $this->quality);
    }

    public static function fromItem(Item $item): self
    {
        $entity = new self();
        $entity->name = $item->name;
        $entity->sellIn = $item->sellIn;
        $entity->quality = $item->quality;

        return $entity;
    }
}

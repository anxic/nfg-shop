<?php

declare(strict_types=1);

namespace WolfShop\DTO;

final class ApiItemDTO
{
    /**
     * @param array<string, mixed>|null $attributes
     */
    public function __construct(
        private int $id,
        private string $name,
        private int $sellIn,
        private int $quality,
        private ?array $attributes = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function getSellIn(): int
    {
        return $this->sellIn;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }
}

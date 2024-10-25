<?php

declare(strict_types=1);

namespace WolfShop\DTO;

final class ApiItemDTO
{
    /**
     * @param ?array<string, mixed> $attributes
     */
    public function __construct(
        private string $id,
        private string $name,
        private int $sellIn,
        private int $quality,
        private ?array $attributes
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

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

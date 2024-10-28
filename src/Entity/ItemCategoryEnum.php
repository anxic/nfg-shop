<?php

declare(strict_types=1);

namespace WolfShop\Entity;

enum ItemCategoryEnum: string
{
    case Normal = 'Normal';
    case Aged = 'Aged';
    case BackstagePass = 'BackstagePass';
    case Legendary = 'Legendary';
    case Conjured = 'Conjured';
}

<?php

declare(strict_types=1);

namespace WolfShop\Validator\Constraints;

use Symfony\Component\Validator\Constraints as Assert;

class ImageConstraints
{
    public static function get(string $maxSize = '8M'): Assert\Image
    {
        return new Assert\Image([
            'maxSize' => $maxSize,
            'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'mimeTypesMessage' => 'Invalid file type. Only JPEG, PNG, and GIF images are allowed.',
            'maxSizeMessage' => "The file is too large. Allowed maximum size is {$maxSize}.",
        ]);
    }
}

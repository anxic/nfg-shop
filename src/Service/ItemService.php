<?php

declare(strict_types=1);

namespace WolfShop\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WolfShop\Entity\ItemEntity;
use WolfShop\Repository\ItemRepository;

final readonly class ItemService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ItemRepository $itemRepository,
        private readonly CloudinaryUploader $cloudinaryUploader,
    ) {
    }

    public function listItems(): array
    {
        return $this->itemRepository->findAll();
    }

    public function getItemByName(string $name): ?ItemEntity
    {
        return $this->itemRepository->findOneBy([
            'name' => $name,
        ]);
    }

    public function uploadItemImage(ItemEntity $item, UploadedFile $file): array
    {
        $imageData = $this->cloudinaryUploader->upload($file, 'items');
        $item->setImageData($imageData);

        $this->entityManager->flush();

        return $imageData;
    }

    public function deleteItemImage(ItemEntity $item): void
    {
        $imageData = $item->getImageData();

        if ($imageData && isset($imageData['publicId'])) {
            $this->cloudinaryUploader->delete($imageData['publicId']);
            $item->setImageData(null);
            $this->entityManager->flush();
        }
    }
}

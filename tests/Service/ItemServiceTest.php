<?php

declare(strict_types=1);

namespace Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WolfShop\Entity\ItemEntity;
use WolfShop\Repository\ItemRepository;
use WolfShop\Service\CloudinaryUploader;
use WolfShop\Service\ItemService;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface&MockObject
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ItemRepository&MockObject
     */
    private ItemRepository $itemRepository;

    /**
     * @var CloudinaryUploader&MockObject
     */
    private CloudinaryUploader $cloudinaryUploader;

    private ItemService $itemService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);
        $this->cloudinaryUploader = $this->createMock(CloudinaryUploader::class);

        $this->itemService = new ItemService(
            $this->entityManager,
            $this->itemRepository,
            $this->cloudinaryUploader
        );
    }

    public function testListItems(): void
    {
        $items = [new ItemEntity(), new ItemEntity()];
        $this->itemRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($items);

        $result = $this->itemService->listItems();

        $this->assertSame($items, $result);
    }

    public function testGetItemByName(): void
    {
        $itemName = 'Test Item';
        $item = new ItemEntity();
        $item->setName($itemName);

        $this->itemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                'name' => $itemName,
            ])
            ->willReturn($item);

        $result = $this->itemService->getItemByName($itemName);

        $this->assertSame($item, $result);
    }

    public function testUploadItemImage(): void
    {
        $item = new ItemEntity();
        /** @var UploadedFile&MockObject $file */
        $file = $this->createMock(UploadedFile::class);
        $imageData = [
            'url' => 'http://example.com/image.jpg',
            'publicId' => '123',
        ];

        $this->cloudinaryUploader
            ->expects($this->once())
            ->method('upload')
            ->with($file, 'items')
            ->willReturn($imageData);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->itemService->uploadItemImage($item, $file);

        $this->assertSame($imageData, $result);
        $this->assertSame($imageData, $item->getImageData());
    }

    public function testDeleteItemImage(): void
    {
        $item = new ItemEntity();
        $imageData = [
            'publicId' => '123',
            'url' => 'http://example.com/image.jpg',
        ];
        $item->setImageData($imageData);

        $this->cloudinaryUploader
            ->expects($this->once())
            ->method('delete')
            ->with('123');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->itemService->deleteItemImage($item);

        $this->assertNull($item->getImageData());
    }
}

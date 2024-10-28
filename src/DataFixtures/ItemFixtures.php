<?php

declare(strict_types=1);

namespace WolfShop\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WolfShop\DTO\ApiItemDTO;
use WolfShop\Factory\ItemFactory;
use WolfShop\Service\ItemService;

class ItemFixtures extends Fixture
{
    public function __construct(
        private ItemFactory $itemFactory,
        private ItemService $itemService,
        private ParameterBagInterface $params
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $itemsData = [
            new ApiItemDTO(1, 'ElePHPant', 20, 30),
            new ApiItemDTO(2, 'Apple AirPods', 10, 20),
            new ApiItemDTO(3, 'Apple iPad Air', 1, 10),
            new ApiItemDTO(4, 'Xiaomi Redmi Note 13', 5, 35),
            new ApiItemDTO(5, 'Samsung Galaxy S23', 5, 80),
        ];

        $projectDir = $this->params->get('kernel.project_dir');
        if (! is_string($projectDir)) {
            throw new \RuntimeException('The "kernel.project_dir" parameter is not defined.');
        }

        foreach ($itemsData as $data) {
            $item = $this->itemFactory->createItemEntityFromApiDTO($data);
            $imagePath = $projectDir . '/src/DataFixtures/assets/' . $item->getName() . '.jpg';
            if (is_file($imagePath)) {
                $uploadedFile = new UploadedFile($imagePath, $item->getName() . '.jpg');
                $this->itemService->uploadItemImage($item, $uploadedFile);
            }
            $manager->persist($item);
        }

        $manager->flush();
    }
}

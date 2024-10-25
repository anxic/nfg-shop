<?php

declare(strict_types=1);

namespace WolfShop\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WolfShop\Entity\ItemEntity;
use WolfShop\Service\ItemService;
use WolfShop\Validator\Constraints\ImageConstraints;

#[Route('/api/items')]
final class ItemController extends AbstractController
{
    public function __construct(
        private readonly ItemService $itemService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('', name: 'list_items', methods: ['GET'])]
    public function listItems(): JsonResponse
    {
        $items = $this->itemService->listItems();
        $json = $this->serializer->serialize($items, 'json');
        return new JsonResponse($json, json: true);
    }

    #[Route('/findBy', name: 'get_item_by_name', methods: ['GET'])]
    public function getItemByName(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        if (! $name) {
            return $this->json([
                'error' => 'No name provided',
            ], status: Response::HTTP_BAD_REQUEST);
        }

        $item = $this->itemService->getItemByName((string) $name);
        if (! $item) {
            return $this->json([
                'error' => 'Item not found',
            ], status: Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($item, 'json');
        return new JsonResponse($json, json: true);
    }

    #[Route('/{item}/image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(ItemEntity $item, Request $request, ValidatorInterface $validator): JsonResponse
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('image');

        if (! $file) {
            return $this->json([
                'error' => 'No image provided',
            ], Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($file, ImageConstraints::get());
        if (count($errors) > 0) {
            return $this->json([
                'error' => 'Invalid image provided',
            ], Response::HTTP_BAD_REQUEST);
        }

        $imageData = $this->itemService->uploadItemImage($item, $file);

        return $this->json([
            'message' => 'Image uploaded successfully',
            'imageData' => $imageData['url'],
        ]);
    }

    #[Route('/{item}/image', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(ItemEntity $item): JsonResponse
    {
        $this->itemService->deleteItemImage($item);

        return $this->json([
            'message' => 'Image deleted successfully',
        ]);
    }
}

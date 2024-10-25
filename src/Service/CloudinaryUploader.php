<?php

declare(strict_types=1);

namespace WolfShop\Service;

use Cloudinary\Cloudinary;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Autoconfigure]
final class CloudinaryUploader
{
    private Cloudinary $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Uploads an image file to Cloudinary under a specified folder.
     *
     * @param UploadedFile $file The file to be uploaded
     * @param string $folder The folder to upload the file to, e.g., "items", "profiles"
     * @return array Returns an associative array with image metadata: 'type', 'publicId', and 'url'
     */
    public function upload(UploadedFile $file, string $folder): array
    {
        $uploadedFile = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
            ]
        );

        return [
            'type' => 'cloudinary',
            'publicId' => $uploadedFile['public_id'],
            'url' => $uploadedFile['secure_url'],
        ];
    }

    /**
     * Deletes an image from Cloudinary based on the provided public ID.
     *
     * @param string $publicId The public ID of the file to delete
     */
    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}

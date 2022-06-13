<?php

declare(strict_types=1);

namespace App\Application\Image\Query;

use App\Domain\Image\ImageRepositoryInterface;

class GetImagesQuery
{
    private readonly ImageRepositoryInterface $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function getAll(): array
    {
        return $this->imageRepository->findAll();
    }
}
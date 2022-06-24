<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Ramsey\Uuid\UuidInterface;

interface ImageRepositoryInterface
{
    public function save(Image $image): void;

    public function getById(UuidInterface $imageId): Image;

    public function findAll(): array;
}
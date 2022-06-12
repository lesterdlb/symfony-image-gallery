<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Ramsey\Uuid\Uuid;

interface ImageRepositoryInterface
{
    public function save(Image $image): void;

    public function getById(Uuid $imageId): Image;

    public function findAll(): array;
}
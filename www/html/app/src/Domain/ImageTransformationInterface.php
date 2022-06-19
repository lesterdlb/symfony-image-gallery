<?php

declare(strict_types=1);

namespace App\Domain;

interface ImageTransformationInterface
{
    public function createThumbnail(
        string $imageFilename,
        int $width,
        int $height,
        int $quality = 90
    ): string|false;
}
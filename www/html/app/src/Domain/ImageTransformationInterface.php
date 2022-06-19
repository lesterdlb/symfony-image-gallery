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

    public function createGrayscaleImage(string $imageFilename): string|false;

    public function createNegateImage(string $imageFilename): string|false;

    public function createSepiaImage(string $imageFilename): string|false;
}
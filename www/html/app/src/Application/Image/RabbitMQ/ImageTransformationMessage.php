<?php

declare(strict_types=1);

namespace App\Application\Image\RabbitMQ;

final class ImageTransformationMessage
{
    public function __construct(
        private readonly string $imageId,
        private readonly string $imageFilename,
        private readonly array $tags,
        private readonly string $description
    ) {
    }

    public function ImageId(): string
    {
        return $this->imageId;
    }

    public function ImageFilename(): string
    {
        return $this->imageFilename;
    }

    public function Tags(): array
    {
        return $this->tags;
    }

    public function Description(): string
    {
        return $this->description;
    }
}
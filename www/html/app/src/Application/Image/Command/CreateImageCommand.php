<?php

declare(strict_types=1);

namespace App\Application\Image\Command;

class CreateImageCommand
{
    public function __construct(
        private readonly string $imageFilename,
        private readonly array $tags,
        private readonly string $description,
    ) {
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

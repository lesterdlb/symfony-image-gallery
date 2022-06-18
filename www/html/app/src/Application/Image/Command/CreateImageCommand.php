<?php

declare(strict_types=1);

namespace App\Application\Image\Command;

class CreateImageCommand
{
    private string $imageFilename;
    private array $tags = [];
    private string $description;

    public function __construct(
        string $imageFilename,
        array $tags,
        string $description
    ) {
        $this->imageFilename = $imageFilename;
        $this->tags          = $tags;
        $this->description   = $description;
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

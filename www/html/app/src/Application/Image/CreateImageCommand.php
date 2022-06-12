<?php

declare(strict_types=1);

namespace App\Application\Image;

class CreateImageCommand {
    private string $id;
    private string $imageFilename;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;
    private array $tags = [];
    private string $description;

    public function __construct(
        string $id,
        string $imageFilename,
        \DateTimeInterface $createdAt,
        \DateTimeInterface $updatedAt,
        array $tags,
        string $description
    ) {
        $this->id = $id;
        $this->imageFilename = $imageFilename;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->tags = $tags;
        $this->description = $description;
    }

    public function Id()
    {
        return $this->id;
    }

    public function ImageFilename(): string
    {
        return $this->imageFilename;
    }

    public function CreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function UpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
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

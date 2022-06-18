<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ImageCreatedDomainEvent extends Event
{
    public function __construct(
        private UuidInterface $imageId,
        private string $description,
        private array $tags,
        private string $imageFilename
    ) {
    }

    public function ImageId(): UuidInterface
    {
        return $this->imageId;
    }

    public function Description(): string
    {
        return $this->description;
    }

    public function Tags(): array
    {
        return $this->tags;
    }

    public function ImageFilename(): string
    {
        return $this->imageFilename;
    }
}
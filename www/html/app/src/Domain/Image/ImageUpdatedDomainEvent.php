<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ImageUpdatedDomainEvent extends Event
{
    public function __construct(
        private UuidInterface $imageId,
        private string $description,
        private array $tags,
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
}
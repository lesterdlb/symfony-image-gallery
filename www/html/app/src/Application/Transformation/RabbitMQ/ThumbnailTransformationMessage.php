<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ;

use App\Domain\QueueMessage;
use Ramsey\Uuid\UuidInterface;

final class ThumbnailTransformationMessage implements QueueMessage
{
    public function __construct(
        private readonly UuidInterface $imageId,
        private readonly string $imageFilename,
        private readonly array $tags,
        private readonly string $description,
    ) {
    }

    public function ImageId(): UuidInterface
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
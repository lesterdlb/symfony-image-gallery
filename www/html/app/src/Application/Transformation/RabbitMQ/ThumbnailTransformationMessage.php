<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ;

use App\Domain\QueueMessage;

final class ImageTransformationMessage implements QueueMessage
{
    public function __construct(
        private readonly string $imageId,
        private readonly string $imageFilename,
        private readonly array $tags,
        private readonly string $description,
        private readonly string $transformationType
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

    public function TransformationType(): string
    {
        return $this->transformationType;
    }
}
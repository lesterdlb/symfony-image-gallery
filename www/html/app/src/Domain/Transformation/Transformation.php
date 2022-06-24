<?php

declare(strict_types=1);

namespace App\Domain\Transformation;

use App\Application\Transformation\RabbitMQ\ThumbnailTransformationMessage;
use App\Domain\AggregateRoot;
use App\Domain\TransformationType;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
final class Transformation extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $imageFilename;

    #[ORM\Column(type: 'string', length: 255)]
    private string $transformationType;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'uuid')]
    private UuidInterface $imageId;

    private function __construct()
    {
    }

    public static function create(
        UuidInterface $id,
        string $imageFilename,
        TransformationType $transformationType,
        UuidInterface $imageId
    ): self {
        $transformation = new self();

        $transformation->id = $id;
        $transformation->imageFilename      = $imageFilename;
        $transformation->transformationType = $transformationType->name;
        $transformation->imageId            = $imageId;
        $transformation->createdAt          = new \DateTime();

        return $transformation;
    }

    public function Id(): UuidInterface
    {
        return $this->id;
    }

    public function ImageFilename(): string
    {
        return $this->imageFilename;
    }

    public function TransformationType(): string
    {
        return $this->transformationType;
    }

    public function CreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function ImageId(): UuidInterface
    {
        return $this->imageId;
    }
}
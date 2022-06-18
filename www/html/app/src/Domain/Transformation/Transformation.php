<?php

declare(strict_types=1);

namespace App\Domain\Transformation;

use App\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
final class Transformation extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $imageFilename;

    #[ORM\Column(type: 'string', length: 255)]
    private string $transformationType;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'uuid')]
    private UuidInterface $imageId;

    private function __construct()
    {
    }

    public static function create(
        string $imageFilename,
        string $transformationType,
        UuidInterface $imageId
    ): self {
        $transformation = new self();

        $transformation->imageFilename      = $imageFilename;
        $transformation->transformationType = $transformationType;
        $transformation->imageId = $imageId;
        $transformation->createdAt = new \DateTime();

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
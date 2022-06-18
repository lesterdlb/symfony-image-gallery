<?php

namespace App\Domain\Image;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
final class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $imageFilename;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'date', nullable: true)]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'json')]
    private array $tags = [];

    #[ORM\Column(type: 'text')]
    private string $description;

    private function __construct()
    {
    }

    public static function create(
        string $imageFilename,
        \DateTimeInterface $createdAt,
        \DateTimeInterface $updatedAt,
        array $tags,
        string $description
    ): self {
        $image = new self();

        $image->imageFilename = $imageFilename;
        $image->createdAt     = $createdAt;
        $image->updatedAt     = $updatedAt;
        $image->tags          = $tags;
        $image->description   = $description;

        return $image;
    }

    public function Id(): UuidInterface
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

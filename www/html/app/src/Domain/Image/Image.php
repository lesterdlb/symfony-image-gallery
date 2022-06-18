<?php

namespace App\Domain\Image;

use App\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
final class Image extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'json')]
    private array $tags = [];

    private function __construct()
    {
    }

    public static function create(
        UuidInterface $id,
        string $description,
        array $tags,
        string $imageFilename
    ): self {
        $image = new self();

        $image->id          = $id;
        $image->description = $description;
        $image->tags        = $tags;

        $image->record(
            new ImageCreatedDomainEvent(
                $id,
                $description,
                $tags,
                $imageFilename
            )
        );

        return $image;
    }

    public function Id(): UuidInterface
    {
        return $this->id;
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

<?php

namespace App\Domain\Image;

use App\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
final class Image extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
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
        string $description,
        array $tags,
    ): self {
        $image = new self();

        $image->description   = $description;
        $image->tags          = $tags;

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

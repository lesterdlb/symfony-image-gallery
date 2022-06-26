<?php

declare(strict_types=1);

namespace App\Application\Image\Command;

class UpdateImageCommand
{
    public function __construct(
        private readonly string $id,
        private readonly array $tags,
        private readonly string $description,
    ) {
    }

    public function Id(): string
    {
        return $this->id;
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

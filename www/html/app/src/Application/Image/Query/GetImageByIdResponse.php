<?php

declare(strict_types=1);

namespace App\Application\Image\Query;

use App\Domain\Image\Image;

class GetImageByIdResponse
{
    private string $id;
    private string $description;
    private array $tags;

    public function __construct(Image $image)
    {
        $this->id          = (string)$image->Id();
        $this->description = $image->Description();
        $this->tags        = $image->Tags();
    }

    public function Id(): string
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

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}
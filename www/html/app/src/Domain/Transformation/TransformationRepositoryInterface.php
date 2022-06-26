<?php

declare(strict_types=1);

namespace App\Domain\Transformation;

use Ramsey\Uuid\UuidInterface;

interface TransformationRepositoryInterface
{
    public function save(Transformation $transformation): void;

    public function getById(UuidInterface $transformationId): Transformation;

    public function findAllByImageId(UuidInterface $imageId): array;

    public function findAll(): array;

    public function search(array $filter): array;
}
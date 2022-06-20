<?php

declare(strict_types=1);

namespace App\Domain\Transformation;

use Ramsey\Uuid\Uuid;

interface TransformationRepositoryInterface
{
    public function save(Transformation $transformation): void;

    public function getById(Uuid $transformationId): Transformation;

    public function findAll(): array;

    public function search(array $filter): array;
}
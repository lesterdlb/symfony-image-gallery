<?php

declare(strict_types=1);

namespace App\Domain;

interface ElasticsearchInterface
{
    public function add(string $transformationId, string $description, array $tags): void;

    public function update(array $transformationIds, string $description, array $tags): void;
}
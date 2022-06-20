<?php

declare(strict_types=1);

namespace App\Application\Transformation\Query;

use App\Domain\ElasticsearchInterface;
use App\Domain\Transformation\TransformationRepositoryInterface;

class SearchTransformationsQuery
{
    private readonly TransformationRepositoryInterface $transformationRepository;
    private readonly ElasticsearchInterface $elasticsearch;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        ElasticsearchInterface $elasticsearch
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->elasticsearch            = $elasticsearch;
    }

    public function __invoke(string $query): array
    {
        $elasticsearchResult = $this->elasticsearch->search($query);

        return $this->transformationRepository->search($elasticsearchResult);
    }
}
<?php

declare(strict_types=1);

namespace App\Application\Transformation\Query;

use App\Domain\Transformation\TransformationRepositoryInterface;

class GetTransformationsQuery
{
    private readonly TransformationRepositoryInterface $transformationRepository;

    public function __construct(TransformationRepositoryInterface $transformationRepository)
    {
        $this->transformationRepository = $transformationRepository;
    }

    public function __invoke(): array
    {
        return $this->transformationRepository->findAll();
    }
}
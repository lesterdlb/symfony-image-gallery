<?php

declare(strict_types=1);

namespace App\Application\Transformation\Query;

use App\Domain\Transformation\TransformationRepositoryInterface;
use Ramsey\Uuid\Uuid;

class GetTransformationsByImageIdQuery
{
    private readonly TransformationRepositoryInterface $transformationRepository;

    public function __construct(TransformationRepositoryInterface $transformationRepository)
    {
        $this->transformationRepository = $transformationRepository;
    }

    public function __invoke(string $imageId): array
    {
        return $this->transformationRepository->findAllByImageId(Uuid::fromString($imageId));
    }
}
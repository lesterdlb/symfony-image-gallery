<?php

declare(strict_types=1);

namespace App\Application\Transformation\Query;

use App\Domain\ElasticsearchInterface;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;

class SearchTransformationsResponse
{
    private array $transformations;

    public function __construct(array $transformations)
    {
        foreach ($transformations as $transformation) {
            $this->transformations[] = [
                'id'            => (string)$transformation->Id(),
                'imageFilename' => $transformation->ImageFilename(),
                'imageId'       => (string)$transformation->ImageId()
            ];
        }
    }

    public function Transformations(): array
    {
        return $this->transformations;
    }
}
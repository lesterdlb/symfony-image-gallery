<?php

declare(strict_types=1);

namespace App\Application\Image\Query;

use App\Domain\Image\ImageRepositoryInterface;
use Ramsey\Uuid\Uuid;

final class GetImageByIdQuery
{
    private readonly ImageRepositoryInterface $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(string $id): GetImageByIdResponse
    {
        return new GetImageByIdResponse(
            $this->imageRepository->getById(Uuid::fromString($id))
        );
    }
}
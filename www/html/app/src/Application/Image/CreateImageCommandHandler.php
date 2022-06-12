<?php

declare(strict_types=1);

namespace App\Application\Image;

use App\Domain\Image\Image;
use App\Domain\Image\ImageRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateImageCommandHandler implements MessageHandlerInterface
{
    private readonly ImageRepositoryInterface $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(CreateImageCommand $command): void
    {
        $image = Image::create(
            $command->ImageFilename(),
            $command->CreatedAt(),
            $command->UpdatedAt(),
            $command->Tags(),
            $command->Description()
        );

        $this->imageRepository->save($image);
    }


}

<?php

declare(strict_types=1);

namespace App\Application\Image\Command;

use App\Domain\Image\Image;
use App\Domain\Image\ImageRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateImageCommandHandler implements MessageHandlerInterface
{
    private readonly ImageRepositoryInterface $imageRepository;
    private readonly MessageBusInterface $messageBus;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        MessageBusInterface $messageBus
    ) {
        $this->imageRepository = $imageRepository;
        $this->messageBus      = $messageBus;
    }

    public function __invoke(UpdateImageCommand $command): void
    {
        $imageId = Uuid::fromString($command->Id());
        $image   = $this->imageRepository->getById($imageId);

        $image->update($command->Description(), $command->Tags());

        $this->imageRepository->save($image);

        $this->messageBus->dispatch(...$image->pullDomainEvents());
    }
}

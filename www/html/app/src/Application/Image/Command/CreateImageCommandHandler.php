<?php

declare(strict_types=1);

namespace App\Application\Image\Command;

use App\Domain\Image\Image;
use App\Domain\Image\ImageRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateImageCommandHandler implements MessageHandlerInterface
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

    public function __invoke(CreateImageCommand $command): void
    {
        $image = Image::create(
            Uuid::uuid4(),
            $command->Description(),
            $command->Tags(),
            $command->ImageFilename()
        );

        $this->imageRepository->save($image);

        $this->messageBus->dispatch(...$image->pullDomainEvents());
    }
}

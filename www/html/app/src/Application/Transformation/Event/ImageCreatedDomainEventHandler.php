<?php

declare(strict_types=1);

namespace App\Application\Transformation\Event;

use App\Application\Transformation\RabbitMQ\ThumbnailTransformationMessage;
use App\Domain\Image\ImageCreatedDomainEvent;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImageCreatedDomainEventHandler implements MessageHandlerInterface
{
    private readonly TransformationRepositoryInterface $transformationRepository;
    private readonly MessageBusInterface $messageBus;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        MessageBusInterface $messageBus
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->messageBus               = $messageBus;
    }

    public function __invoke(ImageCreatedDomainEvent $event): void
    {
        $baseImage = Transformation::create(
            Uuid::uuid4(),
            $event->ImageFilename(),
            TransformationType::ORIGINAL,
            $event->ImageId()
        );

        $this->transformationRepository->save($baseImage);

        // Create Thumbnail
        $this->messageBus->dispatch(
            new ThumbnailTransformationMessage(
                $event->ImageId(),
                $event->ImageFilename(),
                $event->Tags(),
                $event->Description()
            )
        );
    }
}

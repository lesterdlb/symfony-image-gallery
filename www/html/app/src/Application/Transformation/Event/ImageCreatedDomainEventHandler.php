<?php

declare(strict_types=1);

namespace App\Application\Transformation\Event;

use App\Application\Transformation\RabbitMQ\Message\GrayscaleTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\NegateTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\SepiaTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\ThumbnailTransformationMessage;
use App\Domain\ElasticsearchInterface;
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
    private ElasticsearchInterface $elasticsearch;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        MessageBusInterface $messageBus,
        ElasticsearchInterface $elasticsearch
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->messageBus               = $messageBus;
        $this->elasticsearch            = $elasticsearch;
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
        $this->elasticsearch->add(
            (string)$baseImage->Id(),
            $event->Description(),
            $event->Tags()
        );

        $params = [
            $event->ImageId(),
            $event->ImageFilename(),
            $event->Tags(),
            $event->Description()
        ];

        // Transformations
        $this->messageBus->dispatch(
            new ThumbnailTransformationMessage(...$params)
        );
        $this->messageBus->dispatch(
            new GrayscaleTransformationMessage(...$params)
        );
        $this->messageBus->dispatch(
            new SepiaTransformationMessage(...$params)
        );
        $this->messageBus->dispatch(
            new NegateTransformationMessage(...$params)
        );
    }
}

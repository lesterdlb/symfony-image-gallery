<?php

declare(strict_types=1);

namespace App\Application\Transformation\Event;

use App\Domain\ElasticsearchInterface;
use App\Domain\Image\ImageUpdatedDomainEvent;
use App\Domain\Transformation\TransformationRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImageUpdatedDomainEventHandler implements MessageHandlerInterface
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

    public function __invoke(ImageUpdatedDomainEvent $event): void
    {
        $transformations = $this->transformationRepository->findAllByImageId($event->ImageId());

        $ids = [];
        foreach ($transformations as $transformation) {
            $ids[] = (string)$transformation->Id();
        }

        $this->elasticsearch->update(
            $ids,
            $event->Description(),
            $event->Tags()
        );
    }
}

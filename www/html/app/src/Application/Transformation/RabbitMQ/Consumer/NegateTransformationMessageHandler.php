<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ\Consumer;

use App\Application\Transformation\RabbitMQ\Message\GrayscaleTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\NegateTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\SepiaTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\ThumbnailTransformationMessage;
use App\Domain\ElasticsearchInterface;
use App\Domain\ImageTransformationInterface;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use App\Infrastructure\Service\UploadImageService;
use Imagick;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class NegateTransformationMessageHandler implements MessageHandlerInterface
{
    private TransformationRepositoryInterface $transformationRepository;
    private ImageTransformationInterface $imageTransformation;
    private ElasticsearchInterface $elasticsearch;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        ImageTransformationInterface $imageTransformation,
        ElasticsearchInterface $elasticsearch
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->imageTransformation      = $imageTransformation;
        $this->elasticsearch            = $elasticsearch;
    }

    public function __invoke(NegateTransformationMessage $message)
    {
        $filename = $this->imageTransformation
            ->createNegateImage(
                $message->ImageFilename()
            );

        $transformation = Transformation::create(
            Uuid::uuid4(),
            $filename,
            TransformationType::NEGATE,
            $message->ImageId()
        );

        $this->transformationRepository->save($transformation);
        $this->elasticsearch->add(
            (string)$transformation->Id(),
            $message->Description(),
            [...$message->Tags(), TransformationType::NEGATE->name]
        );

        print_r('Negate message handled!' . PHP_EOL);
    }
}

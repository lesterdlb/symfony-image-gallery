<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ\Consumer;

use App\Application\Transformation\RabbitMQ\Message\ThumbnailTransformationMessage;
use App\Domain\ImageTransformationInterface;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use App\Infrastructure\Service\UploadImageService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ThumbnailTransformationMessageHandler implements MessageHandlerInterface
{
    private TransformationRepositoryInterface $transformationRepository;
    private ImageTransformationInterface $imageTransformation;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        ImageTransformationInterface $imageTransformation
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->imageTransformation      = $imageTransformation;
    }

    public function __invoke(ThumbnailTransformationMessage $message)
    {
        $filename = $this->imageTransformation
            ->createThumbnail(
                $message->ImageFilename(),
                400,
                400
            );

        $transformation = Transformation::create(
            Uuid::uuid4(),
            $filename,
            TransformationType::THUMBNAIL,
            $message->ImageId()
        );

        $this->transformationRepository->save($transformation);

        //Elasticsearch

        print_r('Thumbnail image handled!' . PHP_EOL);
    }
}

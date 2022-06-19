<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ\Consumer;

use App\Application\Transformation\RabbitMQ\Message\GrayscaleTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\SepiaTransformationMessage;
use App\Application\Transformation\RabbitMQ\Message\ThumbnailTransformationMessage;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use App\Infrastructure\Service\UploadImageService;
use Imagick;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SepiaTransformationMessageHandler implements MessageHandlerInterface
{
    private TransformationRepositoryInterface $transformationRepository;
    private UploadImageService $uploadImageService;
    private string $uploadFolderPath;

    public function __construct(
        TransformationRepositoryInterface $transformationRepository,
        UploadImageService $uploadImageService,
        string $uploadFolderPath
    ) {
        $this->transformationRepository = $transformationRepository;
        $this->uploadImageService       = $uploadImageService;
        $this->uploadFolderPath         = $uploadFolderPath;
    }

    public function __invoke(SepiaTransformationMessage $message)
    {
        print_r('Sepia message handled!' . PHP_EOL);
    }
}

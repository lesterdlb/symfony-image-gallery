<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ\Consumer;

use App\Application\Transformation\RabbitMQ\ThumbnailTransformationMessage;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Infrastructure\Service\UploadImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ThumbailTransformationMessageHandler implements MessageHandlerInterface
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

    public function __invoke(ThumbnailTransformationMessage $message)
    {
        print_r('image processed! :D' . PHP_EOL);
    }
}

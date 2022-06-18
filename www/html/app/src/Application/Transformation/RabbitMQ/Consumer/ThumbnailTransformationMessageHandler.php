<?php

declare(strict_types=1);

namespace App\Application\Transformation\RabbitMQ\Consumer;

use App\Application\Transformation\RabbitMQ\ThumbnailTransformationMessage;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use App\Infrastructure\Service\UploadImageService;
use Imagick;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ThumbnailTransformationMessageHandler implements MessageHandlerInterface
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
        $filename = $this->generateThumbnail(
            $this->uploadFolderPath . '/',
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

        print_r('image processed! :D' . PHP_EOL);
    }

    private function generateThumbnail(
        $path,
        $imageFilename,
        $width,
        $height,
        $quality = 90
    ) {
        if (is_file($path . $imageFilename)) {
            $imagick = new Imagick($path . $imageFilename);
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($width, $height, true, false);

            $newFilename = explode('.', $imageFilename)[0] . '_thumb' . '.jpg';

            if (file_put_contents($path . $newFilename, $imagick) === false) {
                throw new Exception("Could not put contents.");
            }

            return $newFilename;
        } else {
            throw new Exception("No valid image provided with {$imageFilename}.");
        }
    }
}

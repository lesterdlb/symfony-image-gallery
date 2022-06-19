<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\ImageTransformationInterface;
use Imagick;
use Psr\Log\LoggerInterface;

final class ImageTransformationService implements ImageTransformationInterface
{
    private string $uploadFolderPath;
    private LoggerInterface $logger;

    public function __construct(
        string $uploadFolderPath,
        LoggerInterface $logger
    ) {
        $this->uploadFolderPath   = $uploadFolderPath;
        $this->logger             = $logger;
    }

    public function createThumbnail(
        string $imageFilename,
        int $width,
        int $height,
        int $quality = 90
    ): string|false {
        try {
            if (file_exists($this->uploadFolderPath . $imageFilename)) {
                $imagick = new Imagick($this->uploadFolderPath . $imageFilename);

                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
                $imagick->setImageCompressionQuality($quality);

                $imagick->thumbnailImage($width, $height, true, false);

                $newFilename = explode('.', $imageFilename)[0] . '_thumb' . '.jpg';

                if (file_put_contents($this->uploadFolderPath . $newFilename, $imagick)) {
                    return $newFilename;
                }

                return false;
            }
        } catch (\ImagickException $ex) {
            $this->logger->error('ImagickException', ['ex' => $ex->getMessage()]);
        }

        return false;
    }
}
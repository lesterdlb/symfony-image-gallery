<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\ImageTransformationInterface;
use Imagick;
use Psr\Log\LoggerInterface;

final class ImageTransformationService implements ImageTransformationInterface
{
    private const THUMBNAIL = '_thumbnail.jpg';
    private const GRAYSCALE = '_grayscale.jpg';
    private const NEGATE = '_negate.jpg';
    private const SEPIA = '_sepia.jpg';

    private string $uploadFolderPath;
    private LoggerInterface $logger;

    public function __construct(
        string $uploadFolderPath,
        LoggerInterface $logger
    ) {
        $this->uploadFolderPath = $uploadFolderPath;
        $this->logger           = $logger;
    }

    public function createThumbnail(
        string $imageFilename,
        int $width,
        int $height,
        int $quality = 90
    ): string|false {
        if (file_exists($this->uploadFolderPath . $imageFilename)) {
            try {
                $imagick = new Imagick($this->uploadFolderPath . $imageFilename);

                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
                $imagick->setImageCompressionQuality($quality);

                $imagick->thumbnailImage($width, $height, true, false);

                $newFilename = explode('.', $imageFilename)[0] . self::THUMBNAIL;

                if (file_put_contents($this->uploadFolderPath . $newFilename, $imagick)) {
                    return $newFilename;
                }
            } catch (\ImagickException $ex) {
                $this->logger->error('ImagickException', ['ex' => $ex->getMessage()]);
            }
        }

        return false;
    }

    public function createGrayscaleImage(string $imageFilename): string|false
    {
        if (file_exists($this->uploadFolderPath . $imageFilename)) {
            try {
                $imagick = new Imagick($this->uploadFolderPath . $imageFilename);

                $imagick->setImageType(Imagick::IMGTYPE_GRAYSCALE);

                $newFilename = explode('.', $imageFilename)[0] . self::GRAYSCALE;

                if (file_put_contents($this->uploadFolderPath . $newFilename, $imagick)) {
                    return $newFilename;
                }
            } catch (\ImagickException $ex) {
                $this->logger->error('ImagickException', ['ex' => $ex->getMessage()]);
            }
        }

        return false;
    }

    public function createNegateImage(string $imageFilename): string|false
    {
        if (file_exists($this->uploadFolderPath . $imageFilename)) {
            try {
                $imagick = new Imagick($this->uploadFolderPath . $imageFilename);

                $imagick->negateImage(false);

                $newFilename = explode('.', $imageFilename)[0] . self::NEGATE;

                if (file_put_contents($this->uploadFolderPath . $newFilename, $imagick)) {
                    return $newFilename;
                }
            } catch (\ImagickException $ex) {
                $this->logger->error('ImagickException', ['ex' => $ex->getMessage()]);
            }
        }

        return false;
    }

    public function createSepiaImage(string $imageFilename): string|false
    {
        if (file_exists($this->uploadFolderPath . $imageFilename)) {
            try {
                $imagick = new Imagick($this->uploadFolderPath . $imageFilename);

                $imagick->sepiaToneImage(90);

                $newFilename = explode('.', $imageFilename)[0] . self::SEPIA;

                if (file_put_contents($this->uploadFolderPath . $newFilename, $imagick)) {
                    return $newFilename;
                }
            } catch (\ImagickException $ex) {
                $this->logger->error('ImagickException', ['ex' => $ex->getMessage()]);
            }
        }

        return false;
    }
}
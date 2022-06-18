<?php

declare(strict_types=1);

namespace App\Application\Image\RabbitMQ\Consumer;

use App\Application\Image\RabbitMQ\ImageTransformationMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ImageTransformationMessageHandler implements MessageHandlerInterface
{
    public function __invoke(ImageTransformationMessage $message)
    {
        print_r('message received! :D' . PHP_EOL);
    }
}

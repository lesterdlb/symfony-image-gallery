<?php

declare(strict_types=1);

namespace App\Domain;

final class EntityNotFoundException extends \RuntimeException
{
    public static function forEntityAndIdentifier(string $entity, string $identifier): self
    {
        return new self(sprintf('Unable to find entity "%s" for identifier "%s"', $entity, $identifier));
    }
}

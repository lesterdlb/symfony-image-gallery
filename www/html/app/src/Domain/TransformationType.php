<?php

declare(strict_types=1);

namespace App\Domain;

enum TransformationType
{
    case ORIGINAL;
    case THUMBNAIL;
    case GRAYSCALE;
    case SEPIA;
    case NEGATE;
}
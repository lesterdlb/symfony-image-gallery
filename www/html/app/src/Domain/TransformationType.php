<?php

declare(strict_types=1);

namespace App\Domain;

enum TransformationType
{
    case BASE;
    case THUMBNAIL;
    case GRAYSCALE;
    case SEPIA;
    case NEGATE;
}
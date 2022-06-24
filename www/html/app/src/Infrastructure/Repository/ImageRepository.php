<?php

namespace App\Infrastructure\Repository;

use App\Domain\EntityNotFoundException;
use App\Domain\Image\Image;
use App\Domain\Image\ImageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

class ImageRepository implements ImageRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager   = $entityManager;
        $this->repository      = $this->entityManager->getRepository(Image::class);
    }

    public function save(Image $image): void
    {
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        $cache = $this->entityManager->getConfiguration()->getResultCache();
        $cache->clear();
    }

    public function getById(UuidInterface $imageId): Image
    {
        $image = $this->repository->findOneBy([
            'id' => $imageId,
        ]);

        if ( ! $image instanceof Image) {
            throw EntityNotFoundException::forEntityAndIdentifier('Image', (string)$imageId);
        }

        return $image;
    }

    public function findAll(): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('I');

        $query = $queryBuilder->getQuery()->enableResultCache();

        return $query->getResult();
    }
}

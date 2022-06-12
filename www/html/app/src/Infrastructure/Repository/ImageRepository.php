<?php

namespace App\Infrastructure\Repository;

use App\Domain\EntityNotFoundException;
use App\Domain\Image\Image;
use App\Domain\Image\ImageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImageRepository implements ImageRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager   = $entityManager;
        $this->repository      = $this->entityManager->getRepository(Image::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Image $image): void
    {
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }

    public function getById(Uuid $imageId): Image
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
        return $this->repository->findAll();
    }
}

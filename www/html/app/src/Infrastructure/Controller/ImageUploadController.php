<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Image\Command\CreateImageCommand;
use App\Infrastructure\Form\UploadImageFormType;
use App\Infrastructure\Service\UploadImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ImageUploadController extends AbstractController
{
    private MessageBusInterface $bus;
    private UploadImageService $uploadImageService;

    public function __construct(
        MessageBusInterface $bus,
        UploadImageService $uploadImageService,
    ) {
        $this->bus                = $bus;
        $this->uploadImageService = $uploadImageService;
    }

    #[Route('/upload', name: 'app_image_upload', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $newFilename = $this->uploadImageService->uploadImage($formData['imageFilename']);

            $this->bus->dispatch(
                new CreateImageCommand(
                    $newFilename,
                    explode(',', $formData['tags']),
                    $formData['description']
                )
            );

            $this->addFlash('info', 'Image uploaded successfully!');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/upload.html.twig', [
            'uploadForm'           => $form->createView(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Image\Command\CreateImageCommand;
use App\Application\Image\Query\GetImagesQuery;
use App\Infrastructure\Form\UploadImageFormType;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UploadImageController extends AbstractController
{
    private MessageBusInterface $bus;
    private const UPLOAD_DIR = '/public/uploads';

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    #[Route('/upload', name: 'app_upload_image', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $formData['imageFilename'];
            $destination  = $this->getParameter('kernel.project_dir') . self::UPLOAD_DIR;

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename      = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );

            $this->bus->dispatch(
                new CreateImageCommand(
                    $newFilename,
                    new \DateTime(),
                    new \DateTime(),
                    explode(',', $formData['tags']),
                    $formData['description']
                )
            );

            $this->addFlash('info', 'Image uploaded successfully!');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/upload.html.twig', [
            'imageTransformations'    => [],
            'uploadForm' => $form->createView(),
        ]);
    }
}

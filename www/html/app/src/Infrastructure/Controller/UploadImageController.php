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
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFilename']->getData();
            $destination  = $this->getParameter('kernel.project_dir') . '/public/uploads';

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
                    ['A', 'B'],
                    'Image'
                )
            );

            $this->addFlash('info', 'Image Uploaded');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/upload.html.twig', [
            'images'    => [],
            'imageForm' => $form->createView(),
        ]);
    }
}

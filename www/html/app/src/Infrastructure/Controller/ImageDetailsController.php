<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Image\Command\CreateImageCommand;
use App\Application\Image\Query\GetImageByIdQuery;
use App\Application\Transformation\Query\GetTransformationsByImageIdQuery;
use App\Domain\TransformationType;
use App\Infrastructure\Form\ImageDetailFormType;
use App\Infrastructure\Form\UploadImageFormType;
use App\Infrastructure\Service\UploadImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ImageDetailsController extends AbstractController
{
    private MessageBusInterface $bus;
    private GetImageByIdQuery $getImageByIdQuery;
    private GetTransformationsByImageIdQuery $getTransformationsByImageIdQuery;

    public function __construct(
        MessageBusInterface $bus,
        GetImageByIdQuery $getImageByIdQuery,
        GetTransformationsByImageIdQuery $getTransformationsByImageIdQuery
    ) {
        $this->bus                              = $bus;
        $this->getImageByIdQuery                = $getImageByIdQuery;
        $this->getTransformationsByImageIdQuery = $getTransformationsByImageIdQuery;
    }

    #[Route('/details/{id}', name: 'app_image_details', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $id              = $request->get('id');
        $image           = $this->getImageByIdQuery->__invoke($id);
        $transformations = $this->getTransformationsByImageIdQuery->__invoke($id);

        $form = $this->createForm(ImageDetailFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            //
            
            $this->addFlash('info', 'Image updated successfully!');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/detail.html.twig', [
            'transformations' => $transformations,
            'detailsForm'          => $form->createView(),
        ]);
    }
}
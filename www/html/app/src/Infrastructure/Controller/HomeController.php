<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Image\Command\CreateImageCommand;
use App\Application\Image\Query\GetImagesQuery;
use App\Infrastructure\Form\ImageFormType;
use App\Infrastructure\Form\SearchImageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    private GetImagesQuery $getImagesQuery;

    public function __construct(MessageBusInterface $bus, GetImagesQuery $getImagesQuery)
    {
        $this->getImagesQuery = $getImagesQuery;
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/index.html.twig', [
            'images'    => $this->getImagesQuery->getAll(),
            'searchForm' => $form->createView(),
        ]);
    }
}

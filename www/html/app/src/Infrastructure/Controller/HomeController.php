<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Transformation\Query\GetTransformationsQuery;
use App\Application\Transformation\Query\SearchTransformationsQuery;
use App\Infrastructure\Form\SearchImageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private GetTransformationsQuery $getTransformationsQuery;
    private SearchTransformationsQuery $searchTransformationsQuery;

    public function __construct(
        GetTransformationsQuery $getTransformationsQuery,
        SearchTransformationsQuery $searchTransformationsQuery
    ) {
        $this->getTransformationsQuery    = $getTransformationsQuery;
        $this->searchTransformationsQuery = $searchTransformationsQuery;
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transformations = $this->searchTransformationsQuery->__invoke(
                $form->get('query')->getData()
            );

            return $this->render('images/index.html.twig', [
                'transformations' => $transformations,
                'searchForm'      => $form->createView(),
            ]);
        }

        return $this->render('images/index.html.twig', [
            'transformations' => $this->getTransformationsQuery->getAll(),
            'searchForm'      => $form->createView(),
        ]);
    }
}

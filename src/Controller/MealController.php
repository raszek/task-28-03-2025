<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\SearchMealType;
use App\Service\Meal\MealService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MealController extends AbstractController
{

    public function __construct(
        private readonly MealService $mealService,
    ) {
    }

    #[Route('/', name: 'app_meal')]
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(SearchMealType::class);

        $searchFormData = null;
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchFormData = $searchForm->getData();
        }

        $meals = $this->mealService->list(
            $searchFormData,
            $request->query->get('page', 1)
        );

        return $this->render('meal/index.html.twig', [
            'meals' => $meals,
            'searchForm' => $searchForm
        ]);
    }

    #[Route('/meals/{id}', name: 'app_meal_view')]
    public function view(Meal $meal): Response
    {
        return $this->render('meal/view.html.twig', [
            'meal' => $meal
        ]);
    }
}

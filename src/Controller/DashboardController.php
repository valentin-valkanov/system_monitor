<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Form\PositionStateType;
use App\Form\PositionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function showWeeklyPositions(): Response
    {
        $openPositions = [
            'EURUSD' => ['open_at' => 1.0910, 'order' => 'buy', 'floating_PnL' => 238.05, 'risk' => 185.30],
            'AUDUSD' => ['open_at' => 0.6675, 'order' => 'buy', 'floating_PnL' => -120.85, 'risk'=> 435.00]
            ];

        $closedPositions = [
            'GOLD' => ['open_at' => 2330.00, 'order' => 'buy', 'closed_at' => 2450.25, 'profit' => 380.30],
            'SP500' => ['open_at' => 5300.00, 'order' => 'buy', 'closed_at' => 5428.01, 'profit' => 483.55]
        ];

        return $this->render('dashboard/dashboard.html.twig', [
            'openPositions' => $openPositions,
            'closedPositions' => $closedPositions
        ]);
    }

    #[Route('brokerId/add', name: 'app_position_add')]
    public function addPosition(Request $request, EntityManagerInterface $entityManager): Response
    {
        $position = new Position();
        $positionState = new PositionState();
        $form = $this->createForm(PositionStateType::class, $positionState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($position);
            $entityManager->flush();

            $positionState->setPosition($position);
            $entityManager->persist($positionState);
            $entityManager->flush();
        }

        return $this->render('position/add_position.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
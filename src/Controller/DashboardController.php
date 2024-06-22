<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Form\PositionStateType;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PositionRepository $positionRepository,
    )
    {
    }

    #[Route('/', name: 'app_dashboard')]
    public function showWeeklyPositions(): Response
    {
        $openPositions = $this->positionRepository->findOpenPositions();
        $closedPositions = $this->positionRepository->findClosedPositionsForCurrentWeek();

        return $this->render('dashboard/dashboard.html.twig', [
            'openPositions' => $openPositions,
            'closedPositions' => $closedPositions
        ]);
    }

    #[Route('/position/add', name: 'app_position_add')]
    public function addPosition(Request $request): Response
    {
        $positionState = new PositionState();
        $position = new Position($positionState);
        $form = $this->createForm(PositionStateType::class, $positionState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position->addPositionState($positionState); // Add PositionState to Position
            $this->entityManager->persist($position);
            $this->entityManager->persist($positionState);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_closed_position_show_all');
        }

        return $this->render('position/add_position.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/position/delete/{positionStateId}', name: 'app_position_delete')]
    public function deletePosition(Request $request, int $positionStateId): Response
    {
        $positionState = $this->entityManager->getRepository(PositionState::class)->find($positionStateId);

        if (!$positionState) {
            throw $this->createNotFoundException('PositionState not found.');
        }

        $position = $positionState->getPosition($positionStateId);

        if(!$position){
            throw $this->createNotFoundException('Position not found');
        }
        $this->entityManager->remove($position);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/position/edit/{positionId}', name: 'app_position_edit')]
    public function editPosition(Request $request, int $positionId): Response
    {
        $position = $this->entityManager->getRepository(Position::class)->find($positionId);

        if(!$position){
            throw $this->createNotFoundException('Position not found');
        }

        $lastState = $position->getLastState();

        $newPositionState = new PositionState();
        $newPositionState->setTime($lastState->getTime());
        $newPositionState->setSymbol($lastState->getSymbol());
        $newPositionState->setType($lastState->getType());
        $newPositionState->setVolume($lastState->getVolume());
        $newPositionState->setPriceLevel($lastState->getPriceLevel());
        $newPositionState->setStopLoss($lastState->getStopLoss());
        $newPositionState->setCommission($lastState->getCommission());
        $newPositionState->setDividend($lastState->getDividend());
        $newPositionState->setSwap($lastState->getSwap());
        $newPositionState->setProfit($lastState->getProfit());
        $newPositionState->setSystem($lastState->getSystem());
        $newPositionState->setStrategy($lastState->getStrategy());
        $newPositionState->setAssetClass($lastState->getAssetClass());
        $newPositionState->setGrade($lastState->getGrade());
        $newPositionState->setState($lastState->getState());


        $form = $this->createForm(PositionStateType::class, $newPositionState);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $position->addPositionState($newPositionState);
            $this->entityManager->persist($newPositionState);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('position/edit_position.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    public function showPortfolioHeatMetrics()
    {
        $combinedRisk = $this->portfolioHeat->findCombinedRisk();
        $combinedRiskPercent = $this->portfolioHeat->findCombinedRiskPercent($accountBalance);
        $totalOpenPositions = $this->portfolioHeat->findTotalOpenPositions();
        $newTrades = count($this->positionStateRepository->findNewTrades());
        $closedTrades = count($this->positionStateRepository->findClosedPositionsForCurrentWeek());
        $closedPnL = $this->portfolioHeat->getClosedPnL();
        $openPnL = $this->portfolioHeat->getOpenPnL();
        $account = $accountBalance + $closedPnL;

        return$this->render('dashboard/dashboard.html.twig', [
            'combinedRisk' => $combinedRisk,
            'combinedRiskPercent' => $combinedRiskPercent,
            'totalOpenPositions' => $totalOpenPositions,
            'newTrades' => $newTrades,
            'closedTrades' => $closedTrades,
            'closedPnL' => $closedPnL,
            'openPnL' => $openPnL,
            'account' => $account
        ]);
    }
}
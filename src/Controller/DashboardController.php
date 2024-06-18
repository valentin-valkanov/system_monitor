<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Form\PositionStateType;
use App\Form\PositionType;
use App\Repository\PositionRepository;
use App\Repository\PositionStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PositionStateRepository $positionStateRepository
    )
    {
    }

    #[Route('/', name: 'app_dashboard')]
    public function showWeeklyPositions(): Response
    {
        $openPositions = $this->positionStateRepository->findOpenPositions();
        $closedPositions = $this->positionStateRepository->findClosedPositionsForCurrentWeek();


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

            $this->entityManager->persist($position);
            $this->entityManager->flush();

            $positionState->setPosition($position);
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

    #[Route('/position/edit/{positionStateId}', name: 'app_position_edit')]
    public function editPosition(Request $request, int $positionStateId): Response
    {
        $positionState = $this->entityManager->getRepository(PositionState::class)->find($positionStateId);

        if (!$positionState) {
            throw $this->createNotFoundException('PositionState not found.');
        }

        $position = $positionState->getPosition($positionStateId);


        if(!$position){
            throw $this->createNotFoundException('Position not found');
        }

        $newPositionState = new PositionState();
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
}
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

class PositionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PositionRepository $positionRepository,
    )
    {
    }
    #[Route('position/show', name: 'app_closed_position_show_all')]
    public function showAllPositions(): Response
    {
        $closedPositions = $this->positionRepository->findAllClosedPositions();

        return $this->render('position/closed_positions.html.twig', [
            'closedPositions' => $closedPositions,
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
}
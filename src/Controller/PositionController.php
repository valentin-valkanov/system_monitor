<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\PositionRepository;
use App\Repository\PositionStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $positions = $this->positionRepository->findLatestClosedPositionStates();
        dd($positions);
        return $this->render('position/closed_positions.html.twig', [
            'positions' => $positions,
        ]);
    }
}